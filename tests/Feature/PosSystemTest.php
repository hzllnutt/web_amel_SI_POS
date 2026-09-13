<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PosSystemTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('email', 'admin@gmail.com')->first() ?? User::where('email', 'admin@elscoffee.test')->first();
        $this->kasir = User::where('email', 'kasir@gmail.com')->first() ?? User::where('email', 'kasir@elscoffee.test')->first();

        $this->admin->update(['password' => bcrypt('12345678')]);
        $this->kasir->update(['password' => bcrypt('12345678')]);
    }

    /**
     * Test login page loads.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee("el's", false);
    }

    /**
     * Test authentication works for admin and cashier.
     */
    public function test_user_can_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test role authorization: Cashier cannot access /users (User Management).
     */
    public function test_cashier_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->kasir)->get('/users');
        $response->assertStatus(403);
    }

    /**
     * Test role authorization: Admin can access /users.
     */
    public function test_admin_can_access_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Pengguna');
    }

    /**
     * Test Dashboard loads dynamic stats.
     */
    public function test_dashboard_is_accessible_with_dynamic_data(): void
    {
        $response = $this->actingAs($this->kasir)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan & Analitik Bisnis');
    }

    /**
     * Test Category CRUD: Cannot delete category if used by products.
     */
    public function test_cannot_delete_category_with_products(): void
    {
        $category = Category::where('category_name', 'Coffee')->first();

        $response = $this->actingAs($this->admin)->delete("/categories/{$category->id}");
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error', 'Kategori masih digunakan oleh produk.');
    }

    /**
     * Test Product CRUD: Create new product.
     */
    public function test_can_create_product(): void
    {
        $category = Category::first();

        $response = $this->actingAs($this->admin)->post('/products', [
            'product_name' => 'Kopi Gula Aren Special',
            'category_id' => $category->id,
            'product_price' => 22000,
            'product_stock' => 15,
            'product_description' => 'Espresso dengan susu dan gula aren murni.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'product_name' => 'Kopi Gula Aren Special',
            'product_stock' => 15,
        ]);
    }

    /**
     * Test POS products AJAX catalog endpoint.
     */
    public function test_pos_products_ajax_returns_json(): void
    {
        $response = $this->actingAs($this->kasir)->getJson('/pos/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'products' => [
                '*' => [
                    'id',
                    'product_name',
                    'product_price',
                    'product_stock',
                    'formatted_price',
                    'is_sold_out',
                ]
            ]
        ]);
    }

    /**
     * Test POS checkout with cash payment, stock deduction, and change calculation.
     */
    public function test_pos_checkout_with_cash_deducts_stock_and_creates_order(): void
    {
        $product = Product::where('product_stock', '>', 5)->where('is_active', true)->first();
        $initialStock = $product->product_stock;
        $orderQty = 2;
        $totalPrice = $product->product_price * $orderQty;
        $paid = $totalPrice + 10000; // Kembalian 10.000

        $payload = [
            'payment_method' => 'cash',
            'order_paid' => $paid,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $orderQty,
                ]
            ]
        ];

        $response = $this->actingAs($this->kasir)->postJson('/pos/checkout', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total' => $totalPrice,
            'paid' => $paid,
            'change' => 10000,
        ]);

        // Verify product stock decremented
        $product->refresh();
        $this->assertEquals($initialStock - $orderQty, $product->product_stock);

        // Verify Order record exists
        $orderId = $response->json('order_id');
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $this->kasir->id,
            'order_amount' => $totalPrice,
            'order_paid' => $paid,
            'order_change' => 10000,
            'payment_method' => 'cash',
            'order_status' => 'completed',
        ]);

        // Verify OrderDetail record exists
        $this->assertDatabaseHas('order_details', [
            'order_id' => $orderId,
            'product_id' => $product->id,
            'order_quantity' => $orderQty,
            'order_subtotal' => $totalPrice,
        ]);
    }

    /**
     * Test POS checkout rejects when cash is insufficient.
     */
    public function test_pos_checkout_rejects_insufficient_payment(): void
    {
        $product = Product::where('product_stock', '>', 5)->where('is_active', true)->first();
        $totalPrice = $product->product_price * 2;
        $paid = $totalPrice - 5000; // Kurang

        $payload = [
            'payment_method' => 'cash',
            'order_paid' => $paid,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ];

        $response = $this->actingAs($this->kasir)->postJson('/pos/checkout', $payload);
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test POS checkout rejects when requested quantity exceeds stock.
     */
    public function test_pos_checkout_rejects_quantity_exceeding_stock(): void
    {
        $product = Product::where('is_active', true)->first();
        $excessQuantity = $product->product_stock + 100;

        $payload = [
            'payment_method' => 'cash',
            'order_paid' => 1000000,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $excessQuantity,
                ]
            ]
        ];

        $response = $this->actingAs($this->kasir)->postJson('/pos/checkout', $payload);
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test Order Detail page and Thermal Receipt view.
     */
    public function test_order_detail_and_receipt_are_viewable(): void
    {
        $order = Order::first();
        if ($order) {
            $showRes = $this->actingAs($this->kasir)->get("/orders/{$order->id}");
            $showRes->assertStatus(200);
            $showRes->assertSee($order->order_code);

            $receiptRes = $this->actingAs($this->kasir)->get("/orders/{$order->id}/receipt");
            $receiptRes->assertStatus(200);
            $receiptRes->assertSee("el'sCoffe", false);
            $receiptRes->assertSee('Coffee &amp; Good Mood', false);
        }
    }

    /**
     * Test Order cancellation restores product stock.
     */
    public function test_order_cancellation_restores_product_stock(): void
    {
        $product = Product::where('product_stock', '>', 5)->first();
        $stockBefore = $product->product_stock;

        // Make checkout
        $this->actingAs($this->kasir)->postJson('/pos/checkout', [
            'payment_method' => 'cash',
            'order_paid' => $product->product_price * 2,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ]);

        $product->refresh();
        $this->assertEquals($stockBefore - 2, $product->product_stock);

        $order = Order::latest()->first();

        // Cancel order
        $response = $this->actingAs($this->kasir)->post("/orders/{$order->id}/cancel");
        $response->assertRedirect(route('orders.show', $order->id));

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);

        $product->refresh();
        $this->assertEquals($stockBefore, $product->product_stock);
    }
}
