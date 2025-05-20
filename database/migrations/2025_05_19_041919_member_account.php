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
        Schema::create('member_accounts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
        
            $table->string('member_id'); // UUID, references users.id
            $table->foreign('member_id')->references('id')->on('users');
        
            $table->decimal('simpanan_pokok', 15, 2)->default(0);
            $table->decimal('simpanan_wajib', 15, 2)->default(0);
            $table->decimal('simpanan_sukarela', 15, 2)->default(0);
            $table->decimal('sibuhar', 15, 2)->default(0);
            $table->decimal('debt', 15, 2)->default(0);
        
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
        
            $table->timestamps(); // For tracking changes
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_accounts');
    }
};
