<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'product_price' => 'required|numeric|min:0',
            'product_stock' => 'required|integer|min:0',
            'product_description' => 'nullable|string',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'product_price.required' => 'Harga produk wajib diisi.',
            'product_price.numeric' => 'Harga harus berupa angka.',
            'product_price.min' => 'Harga tidak boleh negatif.',
            'product_stock.required' => 'Stok produk wajib diisi.',
            'product_stock.integer' => 'Stok harus berupa bilangan bulat.',
            'product_stock.min' => 'Stok tidak boleh negatif.',
            'product_photo.image' => 'File foto harus berupa gambar.',
            'product_photo.mimes' => 'Format foto harus berupa jpeg, png, jpg, webp, atau svg.',
            'product_photo.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
