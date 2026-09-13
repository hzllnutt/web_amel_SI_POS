<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    /**
     * Display the POS terminal.
     */
    public function index()
    {
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->orderBy('category_name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('product_name')
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    /**
     * Fetch products via AJAX for real-time search & category filtering.
     */
    public function getProducts(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Product::with('category')
            ->where('is_active', true);

        if ($search) {
            $query->where('product_name', 'like', "%{$search}%");
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('product_name')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'category_name' => $product->category->category_name ?? '',
                'product_name' => $product->product_name,
                'product_price' => (float) $product->product_price,
                'formatted_price' => $product->formatted_price,
                'product_stock' => (int) $product->product_stock,
                'product_photo' => $product->photo_url,
                'product_description' => $product->product_description,
                'is_sold_out' => $product->product_stock <= 0,
                'badge_class' => $product->stock_badge_class,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * Process checkout transaction.
     */
    public function checkout(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $items = $validated['items'];
        $paymentMethod = $validated['payment_method'];
        $orderPaid = (float) $validated['order_paid'];

        try {
            $order = DB::transaction(function () use ($items, $paymentMethod, $orderPaid) {
                $subtotalAmount = 0;
                $detailsToInsert = [];

                // 1. Validate items and lock rows for stock checking
                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new Exception("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                    }

                    if (!$product->is_active) {
                        throw new Exception("Produk {$product->product_name} sedang tidak aktif.");
                    }

                    if ($product->product_stock < $item['quantity']) {
                        throw new Exception("Stok untuk produk {$product->product_name} tidak mencukupi (Tersedia: {$product->product_stock}, Diminta: {$item['quantity']}).");
                    }

                    $itemSubtotal = $product->product_price * $item['quantity'];
                    $subtotalAmount += $itemSubtotal;

                    $detailsToInsert[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $product->product_price,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                // Calculate 11% tax and final total amount
                $taxAmount = round($subtotalAmount * 0.11, 2);
                $totalAmount = $subtotalAmount + $taxAmount;

                // 2. Validate payment amount
                if ($paymentMethod === 'cash') {
                    if ($orderPaid < $totalAmount) {
                        throw new Exception("Jumlah pembayaran tidak mencukupi.");
                    }
                    $orderChange = $orderPaid - $totalAmount;
                } else {
                    // For cashless (QRIS, etc.), exact match
                    $orderPaid = $totalAmount;
                    $orderChange = 0;
                }

                // 3. Generate unique order code: INV-YYYYMMDD-XXXX
                $datePrefix = Carbon::now()->format('Ymd');
                $countToday = Order::whereDate('order_date', Carbon::today())->count() + 1;
                $orderCode = 'INV-' . $datePrefix . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

                // Ensure uniqueness
                while (Order::where('order_code', $orderCode)->exists()) {
                    $countToday++;
                    $orderCode = 'INV-' . $datePrefix . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);
                }

                // 4. Create Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_code' => $orderCode,
                    'order_date' => Carbon::now(),
                    'order_subtotal' => $subtotalAmount,
                    'order_tax' => $taxAmount,
                    'order_amount' => $totalAmount,
                    'order_paid' => $orderPaid,
                    'order_change' => $orderChange,
                    'payment_method' => $paymentMethod,
                    'order_status' => 'completed',
                ]);

                // 5. Create Order Details & Deduct Stock
                foreach ($detailsToInsert as $detail) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $detail['product']->id,
                        'order_quantity' => $detail['quantity'],
                        'order_price' => $detail['price'],
                        'order_subtotal' => $detail['subtotal'],
                    ]);

                    // Deduct stock directly
                    $detail['product']->decrement('product_stock', $detail['quantity']);
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'subtotal' => $order->order_subtotal,
                'tax' => $order->order_tax,
                'total' => $order->order_amount,
                'paid' => $order->order_paid,
                'change' => $order->order_change,
                'redirect_url' => route('orders.show', $order->id),
                'receipt_url' => route('orders.receipt', $order->id),
            ]);
        } catch (Exception $e) {
            Log::error('POS Checkout Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Transaksi gagal diproses.',
            ], 422);
        }
    }
}
