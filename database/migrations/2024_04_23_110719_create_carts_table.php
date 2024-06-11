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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('floor_table_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('diners')->default(1);
            $table->string('client_info')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });


        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('cartable');
            $table->unsignedInteger('quantity')->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();
        });


        Schema::create('promo_code_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_users');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
