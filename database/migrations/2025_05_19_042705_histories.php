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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('account_id'); // link to member_accounts
            $table->string('member_id')->nullable(); // UUID from users (member)
            $table->string('staff_id')->nullable();  // UUID from users (staff)
        
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
        
            $table->enum('direction', [
                'simpanan_pokok',
                'simpanan_wajib',
                'simpanan_sukarela',
                'sibuhar'
            ]);
        
            $table->enum('type', ['deposit', 'withdrawal', 'debt']);
        
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('account_id')->references('id')->on('member_accounts')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
