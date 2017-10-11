<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statistics', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique()->comment('用户id');
            $table->unsignedSmallInteger('join')->default(0)->comment('参加活动次数');
            $table->unsignedSmallInteger('publish')->default(0)->comment('发布活动次数');
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
		Schema::drop('statistics');
	}

}
