<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('admin_id')->nullable(); // admin user

            $table->enum('type', ['payin', 'payout']); // dono ke liye ek hi table

            $table->decimal('commission1', 12, 2)->nullable();
            $table->decimal('percentage1', 5, 2)->nullable();
            $table->decimal('commission2', 12, 2)->nullable();
            $table->decimal('percentage2', 5, 2)->nullable();
            $table->decimal('commission3', 12, 2)->nullable();
            $table->decimal('percentage3', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('commissions');
    }
};
