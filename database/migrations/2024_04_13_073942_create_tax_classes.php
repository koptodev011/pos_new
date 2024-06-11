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
        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('default')->default(false);
            $table->timestamps();
        });


        Schema::create('tax_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zone_type')->index();
            $table->string('price_display');
            $table->boolean('active')->index();
            $table->boolean('default')->index();
            $table->timestamps();
        });


        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_zone_id')->nullable()->constrained('tax_zones');
            $table->tinyInteger('priority')->default(1)->index()->unsigned();
            $table->string('name');
            $table->timestamps();
        });


        Schema::create('tax_rate_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_class_id')->nullable()->constrained('tax_classes');
            $table->foreignId('tax_rate_id')->nullable()->constrained('tax_rates');
            $table->decimal('percentage', 7, 3)->index();
            $table->timestamps();
        });


        Schema::create('tax_zone_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_zone_id')->nullable()->constrained('tax_zones');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->timestamps();
        });


        Schema::create('tax_zone_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_zone_id')->nullable()->constrained('tax_zones');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->timestamps();
        });


        Schema::create('tax_zone_postcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_zone_id')->nullable()->constrained('tax_zones');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('postcode', 20)->index();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_zone_postcodes');
        Schema::dropIfExists('tax_zone_states');
        Schema::dropIfExists('tax_zone_countries');
        Schema::dropIfExists('tax_rate_amounts');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('tax_zones');
        Schema::dropIfExists('tax_classes');
    }
};
