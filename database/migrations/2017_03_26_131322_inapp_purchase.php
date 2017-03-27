<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InappPurchase extends Migration
{
    private $_table = 'inapp_purchase';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->_table, function (Blueprint $table) {
            $table->engine = env('DB_ENGINE', 'MyISAM');
            $table->increments('id');
            $table->string('transaction_id')->unique();
            $table->smallInteger('os');
            $table->text('receipt');
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
        Schema::dropIfExists($this->_table);
    }
}
