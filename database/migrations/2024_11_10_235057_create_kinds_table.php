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
        Schema::create('kinds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('packaging_id');
            $table->unsignedBigInteger('item_id');
            $table->foreign('packaging_id')->references('id')->on('packagings')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('Items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinds');
    }
};