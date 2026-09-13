<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris,debit,ewallet',
            'order_paid' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang belanja tidak boleh kosong.',
            'items.min' => 'Pilih minimal satu produk.',
            'items.*.product_id.required' => 'ID produk wajib ada.',
            'items.*.product_id.exists' => 'Produk tidak ditemukan.',
            'items.*.quantity.required' => 'Jumlah beli wajib ditentukan.',
            'items.*.quantity.min' => 'Jumlah beli minimal 1.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'order_paid.required' => 'Nominal pembayaran wajib diisi.',
            'order_paid.numeric' => 'Nominal pembayaran harus berupa angka.',
            'order_paid.min' => 'Nominal pembayaran tidak valid.',
        ];
    }
}
