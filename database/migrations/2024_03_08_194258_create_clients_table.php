<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->string('inn', 255);
            $table->uuid('tariff_id');
            $table->foreign('tariff_id')->references('id')->on('tariffs');
            $table->string('payment_state', 255);
            $table->string('host', 255);
            $table->dateTime('access_from');
            $table->string('contract', 255);
            $table->string('mail', 255);
            $table->string('description', 255);
            $table->string('agent_fio', 255);
            $table->string('agent_position', 255);
            $table->string('agent_phone', 255);
            $table->string('agent_mail', 255);
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
        Schema::dropIfExists('clients');
    }
}
