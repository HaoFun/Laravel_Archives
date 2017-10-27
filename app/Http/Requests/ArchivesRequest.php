<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchivesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'body'  => 'required|min:10'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '文章標題不能不填寫',
            'body.required'  => '文章內文不能不填寫',
            'body.min'       => '文章內文不能小於10字元'
        ];
    }
}
