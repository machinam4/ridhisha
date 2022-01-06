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
            $table->string("InvoiceNumber")->nullable();
            $table->integer("OrgAccountBalance");
            $table->string("ThirdPartyTransID")->nullable();
            $table->string("MSISDN");
            $table->string("FirstName");
            $table->string("MiddleName")->nullable();
            $table->string("LastName")->nullable();
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
