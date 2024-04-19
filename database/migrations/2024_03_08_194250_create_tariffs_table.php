<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('description', 500)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
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
        Schema::dropIfExists('tariffs');
    }
}
