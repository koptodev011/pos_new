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

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('website')->nullable();
            $table->boolean('active')->default(true);
            $table->json('meta')->nullable();
            $table->string('gst')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('tenant_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('line_one');
            $table->string('line_two')->nullable();
            $table->string('line_three')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->decimal('lattitude', 8, 6)->nullable();
            $table->decimal('longitude', 8, 6)->nullable();
            $table->decimal('altitude', 8, 6)->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('image', 2048)->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('default')->default(true);
            $table->json('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['name', 'tenant_id']);
        });


        Schema::create('user_tenant_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_unit_id')->constrained()->cascadeOnDelete();
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

        Schema::dropIfExists('user_tenant_units');
        Schema::dropIfExists('tenant_units');
        Schema::dropIfExists('tenants');

    }
};
