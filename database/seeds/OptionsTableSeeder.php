<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            [
                'name' => '姓名',
                'type' => 'text',
                'key' => 'name',
                'placeholder' => '姓名',
                'rule' => [
                    'required',
                    'min:2',
                    'max:20',
                ]
            ],
            [
                'name' => '手机号码',
                'type' => 'number',
                'key' => 'phone',
                'placeholder' => '手机号码',
                'rule' => [
                    'required',
                    'regex:/^1(\d){10}$/'
                ]
            ],
            [
                'name' => '身份证号码',
                'type' => 'idcard',
                'key' => 'idcard',
                'placeholder' => '身份证号码',
                'rule' => [
                    'required',
                    'max:18',
                ]
            ],
            [
                'name' => '性别',
                'type' => 'picker',
                'key' => 'gender',
                'placeholder' => '性别',
                'rule' => [
                    'required',
                    'in:1,2'
                ],
                'option_value' => [
                    [
                        'title' => '男',
                        'value' => '1'
                    ],
                    [
                        'title' => '女',
                        'value' => '2'
                    ]
                ]
            ],
            [
                'name' => '年龄',
                'type' => 'number',
                'key' => 'age',
                'placeholder' => '年龄',
                'rule' => [
                    'required',
                    'max:100'
                ]
            ],
        ];

        foreach ($options as &$option) {
            \Library\Tools\Common::transform2DbData($option);
            \App\Entities\Option::create($option);
        }

    }
}
