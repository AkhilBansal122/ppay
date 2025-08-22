<?php

// database/migrations/2025_08_15_000000_create_payouts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // linked to users table
            $table->string('transfer_by');
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->string('ifsc');
            $table->string('bank_name');
            $table->decimal('transfer_amount', 15, 2);
            $table->string('payment_mode');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payouts');
    }
};
