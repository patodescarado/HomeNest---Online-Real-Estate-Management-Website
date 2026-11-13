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
        Schema::create('properties', function (Blueprint $table) {
            $table->id('property_id');
            $table->foreignid('owner_id')->constrained('users')->cascadeondelete();
            $table->foreignid('type_id');
            $table->foreign('type_id')
            ->references('type_id')
            ->on('property_types')
            ->restrictedondelete();
            $table->foreignid('district_id');
            $table->string('title',255);
            $table->text('description') ->nullable();
            $table->string('listing_kind',4);
            $table->decimal('price',15,0);
            $table->decimal('area',10,2);
            $table->smallinteger('bedrooms')-> default(0);
            $table->smallinteger('bathrooms') -> default(0);
            $table->string('address',255);
            $table->string('status',10)->default('pending');
            $table->unsignedinteger('view_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['expires_at', 'status']);
            $table->index(['listing_kind','price']);
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
