<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRendezvousesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rendezvouses', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id')->comment('活动id');
            $table->string('rendezvous')->comment('集合点');
            $table->tinyInteger('sort')->comment('顺序');
            $table->timestamps();

            $table->index('activity_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rendezvouses');
	}

}
