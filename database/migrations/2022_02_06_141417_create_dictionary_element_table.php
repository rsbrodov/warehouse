<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictionaryElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_element', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dictionary_id')->nullable(false);
            $table->foreign('dictionary_id')->references('id')->on('dictionary')->onDelete('cascade');
            $table->string('value', 250);
            $table->dateTime('created_date');
            $table->bigInteger('created_author')->unsigned()->index();
            $table->foreign('created_author')->references('id')->on('users');
            $table->dateTime('update_date');
            $table->bigInteger('updated_author')->unsigned()->index();
            $table->foreign('updated_author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionary_element');
    }
}
