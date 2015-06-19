<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//creating user table
        Schema::create('users', function($t)
        {

        	$t->increments('id');
        	$t->string('username', 30);
        	$t->string('password');
        	$t->string('email', 100);
        	$t->string('name', 300)->nullable();
        	$t->string('mobile', 15);
        	$t->integer('group_id');
            $t->text('apikey');
        	$t->dateTime('lastlogin');
        	$t->string('remember_token');
        	$t->boolean('active')->default(1);
        	$t->timestamps();
        	$t->softDeletes();

        });
        
        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('users');

	}

}
