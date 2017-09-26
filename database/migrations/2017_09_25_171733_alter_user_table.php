<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->comment('性别 0：未知、1：男、2：女');
            $table->string('city')->comment('城市');
            $table->string('province')->comment('省');
            $table->string('country')->comment('国家');
            $table->string('avatar_url')->comment('头像地址');
            $table->string('union_id')->comment('union_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn(['nick_name', 'gender', 'city', 'province', 'country', 'avatar_url', 'union_id']);
        });
    }
}
