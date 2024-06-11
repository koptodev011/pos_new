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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price')->default(0.0);
            $table->integer('min_qty')->default(1);
            $table->integer('priority')->default(0);
            $table->enum('type', ['Veg', 'NonVeg', 'Vegan', 'Eggatarian', 'Other'])->default('Veg');
            $table->boolean('active')->default(true);
            $table->string('resc')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('tax_class_id')->nullable()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('menu_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->decimal('value')->default(0.0);
            $table->enum('type', ['Percentage', 'Fixed'])->default('Fixed');
            $table->enum('validity', ['Forever', 'Recurring', 'Period'])->default('Forever');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('days')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();

        });

        Schema::create('menu_menu_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_option_id')->constrained()->cascadeOnDelete();
            $table->boolean('required')->default(false);
            $table->timestamps();
        });


        Schema::create('category_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('meal_time_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meal_time_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_menu');
        Schema::dropIfExists('meal_time_menu');
        Schema::dropIfExists('menu_menu_option');
        Schema::dropIfExists('menu_prices');
        Schema::dropIfExists('menus');
    }
};
