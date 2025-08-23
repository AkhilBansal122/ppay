<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // DMT Fields
            $table->string('dmt_bc_code')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();

            // Status Fields

            $table->boolean('api_status')->default(0);
            $table->boolean('payout_commission_in_percent')->default(0);
            $table->boolean('node_bypass')->default(0);
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'dmt_bc_code',
                'admin_id',
                'api_status',
                'payout_commission_in_percent',
                'node_bypass',
            ]);
        });
    }
};
