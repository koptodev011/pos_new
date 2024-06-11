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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['Coupon'])->default('Coupon');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->enum('value_type', ['Fixed', 'Percentage'])->default('Percentage');
            $table->decimal('value')->default(0);
            $table->decimal('min_value')->default(0);
            $table->decimal('max_value')->default(0);
            $table->integer('limit')->default(0);
            $table->boolean('active')->default(true);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
