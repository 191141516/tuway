<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ActivityUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2|max:120',
            'content' => 'required|min:10|max:2500',
            'pic' => 'required|url',
            'total' => 'required|integer|max:10000',
            'phone' => [
                'required',
                'regex:/^1(\d){10}$/'
            ],
            'price' => 'required|numeric',
            'address' => 'required|max:128',
            'options' => 'required|array',
            'start_date' => 'required|after_or_equal:'.Carbon::now()->addMinute(1)->format('Y-m-d H:i'),
            'end_date' => 'required|after:start_date',
            'images' => 'array'
        ];
    }
}
