<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string("TransactionType");
            $table->string("TransID");
            $table->dateTime("TransTime");
            $table->integer("TransAmount");
            $table->string("BusinessShortCode");
            $table->string("BillRefNumber");
            $table->string("InvoiceNumber");
            $table->integer("OrgAccountBalance");
            $table->string("ThirdPartyTransID");
            $table->string("MSISDN");
            $table->string("FirstName");
            $table->string("MiddleName");
            $table->string("LastName");
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
        Schema::dropIfExists('players');
    }
}
