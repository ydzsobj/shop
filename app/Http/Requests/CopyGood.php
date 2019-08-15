<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CopyGood extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:goods,name',
            'admin_user_id' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.unique' => '复制的单品名称不唯一',
            'name.required' => '单品名称必填',
            'admin_user_id.required' => '所属人必填',
        ];
    }
}
