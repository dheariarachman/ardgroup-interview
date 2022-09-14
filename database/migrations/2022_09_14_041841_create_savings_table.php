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
        Schema::create('savings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->double('balance');
            $table->date('saving_at');
            $table->string('last_transaction_type');
            $table->date('last_transaction_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings');
    }
};
