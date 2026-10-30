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
        Schema::create('property_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->foreignkey('property_id')->constrained()->cascadeondelete();
            $table->string('image_path',255);
            $table->boolean('is_cover')-> default('false');
            $table->smallinterger('sort_order')->default(0);
            $table->biginterger('cover_key');
            $table->timestamp()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
