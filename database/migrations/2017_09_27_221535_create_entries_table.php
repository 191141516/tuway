<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entries', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id')->comment('活动id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('name', 32)->comment('姓名');
            $table->char('phone', 11)->nullable()->comment('手机号码');
            $table->char('id_card', 20)->nullable()->comment('身份证号码');
            $table->tinyInteger('gender')->nullable()->comment('性别 0：未知、1：男、2：女');
            $table->char('age', 20)->nullable()->comment('年龄');
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
		Schema::drop('entries');
	}

}
