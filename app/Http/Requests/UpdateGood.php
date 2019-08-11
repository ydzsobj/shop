<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGood extends FormRequest
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
        $id = $this->route('good');
        return [
            'name' => 'required|unique:goods,name,'.$id.'|max:255',
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '单品展示名必填',
            'name.unique'  => '单品名已存在',
        ];
    }
}
