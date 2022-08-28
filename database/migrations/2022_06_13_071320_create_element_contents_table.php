<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_global');
            $table->uuid('type_content_id');
            $table->foreign('type_content_id')->references('id')->on('type_contents');
            $table->string('label', 150);
            $table->string('api_url', 150);
            $table->text('description')->nullable();
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_after')->nullable();
            $table->enum('status', ['Draft', 'Published', 'Archive', 'Destroy'])->default('Draft');
            $table->integer('version_major')->default('1');
            $table->integer('version_minor')->default('0');
            $table->string('body', 1000);
            $table->uuid('based_element')->nullable();
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
        Schema::dropIfExists('element_contents');
    }
}
