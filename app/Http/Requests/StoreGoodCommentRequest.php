<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodCommentRequest extends FormRequest
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
            'good_id' => 'required|numeric',
            'comment' => 'required|string|max:255',
        ];
    }

    public function messages(){
        return [
            'good_id.required' => '商品id必须',
            'comment.required' => '评价必须',
        ];
    }
}
