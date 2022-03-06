<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_global');
            $table->string('name', 150);
            $table->string('description', 500)->nullable();
            $table->uuid('owner')->nullable();
            $table->string('icon', 150)->nullable();
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_after')->nullable();
            $table->enum('status', ['Draft', 'Published', 'Archive'])->default('Draft');
            $table->integer('version_major')->default('1');
            $table->integer('version_minor')->default('0');
            $table->string('api_url', 150)->unique();
            $table->string('body', 1000)->nullable();
            $table->uuid('based_type')->nullable();
            $table->dateTime('created_at');
            $table->bigInteger('created_author')->unsigned()->index();
            $table->foreign('created_author')->references('id')->on('users');
            $table->dateTime('updated_at');
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
        Schema::dropIfExists('type_contents');
    }
}
