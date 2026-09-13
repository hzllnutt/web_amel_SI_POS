<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('restrict');
            $table->string('order_code')->unique()->index();
            $table->dateTime('order_date')->index();
            $table->decimal('order_subtotal', 12, 2)->default(0);
            $table->decimal('order_tax', 12, 2)->default(0);
            $table->decimal('order_amount', 12, 2);
            $table->decimal('order_paid', 12, 2);
            $table->decimal('order_change', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'ewallet']);
            $table->enum('order_status', ['completed', 'cancelled'])->default('completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
