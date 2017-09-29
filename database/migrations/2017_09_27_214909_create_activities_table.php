<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activities', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('发布的用户id');
            $table->string('title', 128)->comment('活动标题');
            $table->text('content')->comment('活动详情');
            $table->string('pic')->comment('活动主图');
            $table->smallInteger('total')->comment('人数限制');
            $table->string('phone')->comment('组织者电话');
            $table->decimal('price')->comment('活动费用');
            $table->string('address', 256)->comment('活动地址');
            $table->string('options')->comment('报名必填项');
            $table->smallInteger('num')->default(0)->comment('已报名人数');
            $table->dateTime('start_date')->comment('开始时间');
            $table->dateTime('end_date')->comment('结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态 1.报名中 2.活动中 3.活动结束');
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
		Schema::drop('activities');
	}

}
