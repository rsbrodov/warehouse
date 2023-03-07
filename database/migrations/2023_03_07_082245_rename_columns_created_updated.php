<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsCreatedUpdated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_date');
            $table->renameColumn('updated_at', 'update_date');
        });
        Schema::table('type_contents', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_date');
            $table->renameColumn('updated_at', 'update_date');
        });
        Schema::table('element_contents', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_date');
            $table->renameColumn('updated_at', 'update_date');
        });
        Schema::table('dictionary_element', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_date');
            $table->renameColumn('updated_at', 'update_date');
        });
        Schema::table('dictionary', function (Blueprint $table) {
            $table->renameColumn('created_at', 'created_date');
            $table->renameColumn('updated_at', 'update_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('created_date', 'created_at');
            $table->renameColumn('update_date', 'updated_at');
        });
        Schema::table('type_contents', function (Blueprint $table) {
            $table->renameColumn('created_date', 'created_at');
            $table->renameColumn('update_date', 'updated_at');
        });

        Schema::table('element_contents', function (Blueprint $table) {
            $table->renameColumn('created_date', 'created_at');
            $table->renameColumn('update_date', 'updated_at');
        });
        Schema::table('dictionary_element', function (Blueprint $table) {
            $table->renameColumn('created_date', 'created_at');
            $table->renameColumn('update_date', 'updated_at');
        });
        Schema::table('dictionary', function (Blueprint $table) {
            $table->renameColumn('created_date', 'created_at');
            $table->renameColumn('update_date', 'updated_at');
        });
    }
}
