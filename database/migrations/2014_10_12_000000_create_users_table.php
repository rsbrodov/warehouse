<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->enum('status', ['MODERATED', 'ACTIVATED', 'DELETED', 'BLOCKED'])->default('MODERATED');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo', 150)->default('profiles/default.png');
            $table->string('description', 255)->nullable();
            $table->rememberToken();
            $table->dateTime('created_date');
            $table->dateTime('update_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
