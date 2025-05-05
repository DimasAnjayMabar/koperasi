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
        Schema::create('travels', function (Blueprint $table){
            $table -> id();
            $table -> string('destination');
            $table -> string('description');
            $table -> decimal('price');
            $table -> enum('destination_type', ['local', 'intercity', 'overseas']);
            $table -> boolean('is_active') -> default(true);
            $table -> dateTime('deleted_at') -> nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
