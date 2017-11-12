<?php

namespace App\Http\Requests\Admin;

use App\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOperateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => [
                'required',
                'max:32',
                Rule::unique('users')->where(function ($query) use ($id){
                    $query->where('is_operate', User::OPERATE_USER)->where('id', '<>', $id);
                }),
            ],
            'avatar_url' => 'required|url'
        ];
    }

    public function messages()
    {
        return [
            'name.exists' => '运营用户名已存在,请更改'
        ];
    }
}
