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
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->enum('type', ['prepaid', 'postpaid'])->default('prepaid');
            $table->string('amount')->nullable();
            $table->string('operator')->nullable();
            $table->string('status')->default('requested');
            $table->integer('user_id');
            $table->string('order_id')->nullable();
            $table->string('tx_id')->nullable();
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
        Schema::dropIfExists('recharges');
    }
};
