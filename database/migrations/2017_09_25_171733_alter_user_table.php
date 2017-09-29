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
            $table->tinyInteger('gender')->default(0)->comment('性别 0：未知、1：男、2：女');
            $table->string('city')->nullable()->comment('城市');
            $table->string('province')->nullable()->comment('省');
            $table->string('country')->nullable()->comment('国家');
            $table->string('avatar_url')->nullable()->comment('头像地址');
            $table->string('union_id')->nullable()->comment('union_id');
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
            $table->dropColumn(['gender', 'city', 'province', 'country', 'avatar_url', 'union_id']);
        });
    }
}
