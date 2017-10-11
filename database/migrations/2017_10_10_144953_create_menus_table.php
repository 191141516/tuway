<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->comment('父级菜单id');
            $table->string('name', 32)->comment('菜单名称');
            $table->string('route_name')->comment('路由名');
            $table->tinyInteger('type')->comment('菜单类型 1:菜单， 2：操作');
            $table->tinyInteger('sort')->comment('排序');
            $table->tinyInteger('status')->comment('状态: 1启用 2停用');

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
		Schema::drop('menus');
	}

}
