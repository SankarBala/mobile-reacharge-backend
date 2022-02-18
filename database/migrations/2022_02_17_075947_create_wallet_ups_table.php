<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_ups', function (Blueprint $table) {
            $table->id();
            $table->string('tx_id')->nullable();
            $table->string('msg')->nullable();
            $table->string('bank_tx_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('sp_code')->nullable();
            $table->string('sp_code_des')->nullable();
            $table->string('sp_payment_option')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_name')->nullable();
            $table->string('paid_by')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_ups');
    }
};
