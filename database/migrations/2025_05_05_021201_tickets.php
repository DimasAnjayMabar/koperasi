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
        Schema::create('tickets', function (Blueprint $table){
            $table -> id();
            $table -> unsignedBigInteger('travel_id');
            $table -> unsignedBigInteger('transportation_id');
            $table -> decimal('total_price');
            $table -> dateTime('booked_at');
            $table -> boolean('is_active');
            $table -> dateTime('deleted_at');

            $table -> foreign('travel_id') -> references('id') -> on('travels') -> onDelete('cascade');
            $table -> foreign('transportation_id') -> references('id') -> on('transportations') -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
