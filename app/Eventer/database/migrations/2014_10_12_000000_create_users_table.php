<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void            $table->timestamp('deleted_at')->nullable();

     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->enum('user_role', ['agent', 'admin', 'customer'])->default('customer');
            
            $table->string('phone_number')->nullable();

            $table->timestamp('last_logged_in');
            $table->boolean('status')->default(true);

            $table->json('agent_list')->nullable();
            $table->json('customer_list')->nullable();
            $table->bigInteger('cart_id')->nullable();

            $table->bigInteger('address_id')->nullable();
            // $table->foreign('address_id')->references('id')->on('addresses');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
