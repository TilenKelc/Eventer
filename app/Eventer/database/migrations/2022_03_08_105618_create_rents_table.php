<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');

            $table->bigInteger('agent_id')->nullable();
            // $table->unsignedBigInteger('agent_id');
            // $table->foreign('agent_id')->references('id')->on('users');

            $table->string("braintree_transaction_id")->nullable();
            $table->timestamp("payment_transaction_timestamp")->nullable();

            $table->string("braintree_refund_transaction_id")->nullable();
            $table->timestamp("refund_transaction_timestamp")->nullable();

            $table->boolean('ready_for_take_over')->default(false);

            $table->json('equipment_ids')->nullable();
            $table->json('reservation_ids')->nullable();
            $table->enum('status', ['pending', 'successfully_paid', 'in_progress','completed','canceled'])->default('pending');
            $table->dateTime('rental_from')->nullable();
            $table->dateTime('rental_to')->nullable();

            $table->bigInteger('canceled_by')->nullable();
            $table->dateTime('canceled_timestamp')->nullable();
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
        Schema::dropIfExists('rents');
    }
}
