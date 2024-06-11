<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no', 50);
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('floor_table_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('diners')->default(1);
            // To Save Taxes, Discount, Delivery and Promo Code Amount
            // { 'tax': { 'text': 'Zomato', value: 50 }, discount: { 'text': 'Zomato', value: 50 }, promo: { 'text': 'Zomato', value: 50 }, delivery: { 'text': '3 Km', value: 10 }}
            $table->string('code')->nullable();
            $table->json('summary')->nullable();
            $table->json('customer')->nullable();
            $table->json('address')->nullable();
            $table->json('shipping')->nullable();
            $table->json('meta')->nullable();
            $table->string('status')->default('Placed');
            $table->softDeletes();

            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();

            $table->timestamps();
        });


        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('orderable');
            // To Save Other information if needed
            $table->json('summary')->nullable();
            $table->decimal('price');
            $table->unsignedInteger('quantity')->default(1);
            $table->json('meta')->nullable();

            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();

            $table->timestamps();
        });


        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_provider')->default('Stripe');
            $table->json('payment_provider_response')->nullable();
            $table->boolean('paid')->default(false);
            $table->decimal('amount');
            $table->json('meta')->nullable();
            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });


        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('status')->default('Placed');
            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

    }
};
