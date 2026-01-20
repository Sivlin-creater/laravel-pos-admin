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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();
            
            // subtotal decimal(10, 2) default 0.00
            // tax, change_amount, user_id ALTER TABLE sales ADD user_id BIGINT UNSIGNED AFTER payment_method_id; 
            // ALTER TABLE sales
            // ADD CONSTRAINT sales_user_id_foreign
            // FOREIGN KEY (user_id) REFERENCES users(id)
            // ON DELETE SET NULL;

            $table->decimal('total', 8, 2);
            $table->decimal('paid_amount', 8, 2);
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
