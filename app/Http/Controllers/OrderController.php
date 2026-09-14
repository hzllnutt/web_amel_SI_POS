<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with filters and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $date = $request->query('date');
        $payment = $request->query('payment_method');
        $status = $request->query('order_status');

        $orders = Order::with(['user', 'orderDetails.product'])
            ->when($search, function ($query, $search) {
                return $query->where('order_code', 'like', "%{$search}%");
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('order_date', $date);
            })
            ->when($payment, function ($query, $payment) {
                return $query->where('payment_method', $payment);
            })
            ->when($status, function ($query, $status) {
                return $query->where('order_status', $status);
            })
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search', 'date', 'payment', 'status'));
    }

    /**
     * Display the specified order detail.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product.category']);
        return view('orders.show', compact('order'));
    }

    /**
     * Display receipt view for printing.
     */
    public function receipt(Order $order)
    {
        $order->load(['user', 'orderDetails.product']);
        return view('orders.receipt', compact('order'));
    }

    /**
     * Display Sales Report for Pimpinan and Admin (Daily, Weekly, Monthly).
     */
    public function report(Request $request)
    {
        $period = $request->query('period', 'daily');
        $date = $request->query('date', Carbon::today()->format('Y-m-d'));
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $query = Order::with(['user', 'orderDetails.product'])
            ->where('order_status', 'completed');

        $periodTitle = '';

        if ($period === 'weekly') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('order_date', [$startOfWeek, $endOfWeek]);
            $periodTitle = 'This Week (' . $startOfWeek->translatedFormat('d M Y') . ' - ' . $endOfWeek->translatedFormat('d M Y') . ')';
        } elseif ($period === 'monthly') {
            $query->whereYear('order_date', $year)->whereMonth('order_date', $month);
            $periodTitle = '' . Carbon::create($year, $month, 1)->translatedFormat('F Y');
        } else {
            // daily
            $query->whereDate('order_date', $date);
            $periodTitle = '' . Carbon::parse($date)->translatedFormat('l, d F Y');
        }

        // Summary Statistics
        $totalTransactions = (clone $query)->count();
        $totalSubtotal = (clone $query)->sum('order_subtotal');
        $totalTax = (clone $query)->sum('order_tax');
        $totalAmount = (clone $query)->sum('order_amount');
        $cashAmount = (clone $query)->where('payment_method', 'cash')->sum('order_amount');
        $qrisAmount = (clone $query)->where('payment_method', 'qris')->sum('order_amount');

        $orders = $query->latest('order_date')->get();

        return view('reports.sales', compact(
            'orders',
            'period',
            'date',
            'month',
            'year',
            'periodTitle',
            'totalTransactions',
            'totalSubtotal',
            'totalTax',
            'totalAmount',
            'cashAmount',
            'qrisAmount'
        ));
    }

    /**
     * Cancel an order and restore product stock (Admin only).
     */
    public function cancel(Order $order)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat membatalkan transaksi.');
        }

        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($order) {
            // Restore stock for all products in this order
            foreach ($order->orderDetails as $detail) {
                if ($detail->product) {
                    $detail->product->increment('product_stock', $detail->order_quantity);
                }
            }

            $order->update([
                'order_status' => 'cancelled',
            ]);
        });

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Transaksi berhasil dibatalkan dan stok produk telah dikembalikan.');
    }
}
