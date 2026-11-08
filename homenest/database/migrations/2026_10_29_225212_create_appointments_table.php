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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointments_id');
            $table->foreignid('property_id');
            $table->foreign('property_id')
            ->references('property_id')
            ->on('properties')
            ->cascadeondelete();
            $table->foreignid('user_id')->constrained()->cascadeondelete();
            $table->datetime('scheduled_at');
            $table->text('note');
            $table->string('status',10)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
