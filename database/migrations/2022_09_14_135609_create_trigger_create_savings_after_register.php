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
        DB::unprepared('CREATE TRIGGER tr_Create_Savings_After_Register AFTER INSERT ON `users` FOR EACH ROW 
            BEGIN
                INSERT INTO `savings` (`user_id`, `balance`, `saving_at`, `last_transaction_type`, `last_transaction_date`)
                VALUES ( NEW.id, 0, now(), "open", now());
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_Create_Savings_After_Register');
    }
};
