<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' =>[
                'required',
                'max:255',
                Rule::unique('goods')->where(function ($query) use ($id) {
                    $query->whereNull('deleted_at')->where('id', '<>', $id);
                })
            ],
            'title' => 'required',
            'detail_desc' => 'required',
//            'size_desc' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '单品展示名必填',
            'name.unique'  => '单品名已存在',
            'detail_desc.required'  => '详情描述必填',
//            'size_desc.required'  => '尺寸描述必填',
        ];
    }
}
