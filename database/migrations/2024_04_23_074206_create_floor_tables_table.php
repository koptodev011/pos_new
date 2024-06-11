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
        Schema::create('floor_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_capacity')->default(1);
            $table->integer('max_capacity')->default(1);
            $table->string('floor')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('extra_capacity')->default(0);
            $table->integer('priority')->default(0);
            $table->foreignId('tenant_unit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floor_tables');
    }
};
