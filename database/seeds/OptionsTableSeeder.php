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
                ],
                'messages' => [
                    'name.required' => '姓名必填',
                    'name.min' => '姓名最小2位',
                    'name.max' => '姓名最大20位',
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
                ],
                'messages' => [
                    'phone.required' => '手机号码必填',
                    'phone.regex' => '手机号码不正确'
                ]
            ],
            [
                'name' => '身份证号码',
                'type' => 'idcard',
                'key' => 'idcard',
                'placeholder' => '身份证号码',
                'rule' => [
                    'required',
                    'regex:/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/',
                ],
                'messages' => [
                    'idcard.required' => '身份证号码必填',
                    'idcard.regex' => '身份证号码不正确'
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
                ],
                'messages' => [
                    'gender.required' => '性别必填',
                    'gender.in' => '性别值异常',
                ]
            ],
            [
                'name' => '年龄',
                'type' => 'number',
                'key' => 'age',
                'placeholder' => '年龄',
                'rule' => [
                    'required',
                    'integer',
                    'max:100'
                ],
                'messages' => [
                    'age.required' => '年龄必填',
                    'age.integer' => '年龄必须是整数',
                    'age.max' => '年龄最大值100',
                ]
            ],
        ];

        DB::table('options')->delete();
        DB::table('options')->truncate();

        foreach ($options as &$option) {
            \Library\Tools\Common::transform2DbData($option);
            \App\Entities\Option::create($option);
        }

    }
}
