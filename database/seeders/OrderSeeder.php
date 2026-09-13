<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasir = User::where('email', 'kasir@elscoffee.test')->first() ?? User::first();
        $products = Product::where('is_active', true)->where('product_stock', '>', 5)->get();

        if ($products->isEmpty()) {
            return;
        }

        // Generate orders for the past 6 days + today
        $paymentMethods = ['cash', 'qris', 'debit', 'ewallet'];
        $orderCounter = 1;

        for ($d = 6; $d >= 0; $d--) {
            $date = Carbon::today()->subDays($d);
            // 2 to 4 transactions per day
            $dailyCount = rand(2, 4);

            for ($t = 0; $t < $dailyCount; $t++) {
                $orderDate = $date->copy()->setHour(rand(8, 21))->setMinute(rand(0, 59));
                $code = 'INV-' . $orderDate->format('Ymd') . '-' . str_pad($orderCounter++, 4, '0', STR_PAD_LEFT);
                $method = $paymentMethods[array_rand($paymentMethods)];

                // Pick 1 to 3 random products
                $pickedProducts = $products->random(rand(1, 3));
                $subtotal = 0;
                $detailsData = [];

                foreach ($pickedProducts as $prod) {
                    $qty = rand(1, 3);
                    $itemSubtotal = $prod->product_price * $qty;
                    $subtotal += $itemSubtotal;

                    $detailsData[] = [
                        'product_id' => $prod->id,
                        'order_quantity' => $qty,
                        'order_price' => $prod->product_price,
                        'order_subtotal' => $itemSubtotal,
                    ];
                }

                $tax = round($subtotal * 0.11, 2);
                $total = $subtotal + $tax;

                $paid = $method === 'cash' ? (ceil($total / 10000) * 10000) : $total;
                if ($paid < $total) {
                    $paid = $total + 5000;
                }
                $change = $paid - $total;

                $order = Order::create([
                    'user_id' => $kasir->id,
                    'order_code' => $code,
                    'order_date' => $orderDate,
                    'order_subtotal' => $subtotal,
                    'order_tax' => $tax,
                    'order_amount' => $total,
                    'order_paid' => $paid,
                    'order_change' => $change,
                    'payment_method' => $method,
                    'order_status' => 'completed',
                ]);

                foreach ($detailsData as $det) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $det['product_id'],
                        'order_quantity' => $det['order_quantity'],
                        'order_price' => $det['order_price'],
                        'order_subtotal' => $det['order_subtotal'],
                    ]);
                }
            }
        }
    }
}
