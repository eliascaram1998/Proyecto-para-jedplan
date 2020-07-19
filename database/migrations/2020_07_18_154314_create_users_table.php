<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->timestamps();
			$table->string('username',20);
			$table->string('email',30);
			$table->string('password',16);
			$table->date('birthday')->nullable();
			$table->string('sex',9)->nullable();
			$table->string('lat',25)->nullable();
			$table->string('lng',25)->nullable();
			$table->string('adress')->nullable();
			$table->integer('height')->nullable();
			$table->rememberToken();
			$table->integer('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
