<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('options', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32)->comment('选项名');
            $table->string('key', 32)->comment('接收的键');
            $table->string('type', 20)->comment('表单类型');
            $table->string('rule', 128)->comment('验证规则');
            $table->string('option_value', 256)->nullable()->comment('可选值');
            $table->string('messages', 512)->nullable()->comment('验证提示信息');
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
		Schema::drop('options');
	}

}
