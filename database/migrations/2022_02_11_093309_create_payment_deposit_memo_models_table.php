<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDepositMemoModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_deposit_memo_models', function (Blueprint $table) {
            $table->id();
            $table->date('payment_depositeTime');
            $table->double('payment_depositePaid', 15, 4);
            $table->double('payment_subTotal', 15, 4);
            $table->double('payment_remain', 15, 4);
            $table->string('payment_depositePaidBy');
            $table->string('payment_depositeNotes');
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
        Schema::dropIfExists('payment_deposit_memo_models');
    }
}
