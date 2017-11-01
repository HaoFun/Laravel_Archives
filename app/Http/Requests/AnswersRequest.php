<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|min:10'
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '回覆內容不能不填寫',
            'body.min'      => '回覆內容過短'
        ];
    }
}
