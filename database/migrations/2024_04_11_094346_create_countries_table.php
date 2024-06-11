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
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('iso3')->unique();
            $table->string('iso2')->nullable()->unique();
            $table->string('phonecode');
            $table->string('capital')->nullable();
            $table->string('currency');
            $table->string('native')->nullable();
            $table->string('emoji');
            $table->string('emoji_u');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
