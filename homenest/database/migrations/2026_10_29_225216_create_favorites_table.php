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
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignid('user_id')->constrained('users')->cascadeondelete();
            $table->foreignid('property_id');
            $table->foreign('property_id')
            ->references('property_id')
            ->on('properties')
            ->cascadeondelete();
            $table->timestamps();
            $table->primary(['user_id','property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
