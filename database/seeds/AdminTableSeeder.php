<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Entities\Admin();
        $admin->name = '超级管理员';
        $admin->email = 'super@admin.com';
        $admin->password = bcrypt('superman');
        $admin->save();
    }
}
