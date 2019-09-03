<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodCommentRequest extends FormRequest
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
            'comment' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'star_scores' => 'required|numeric',
        ];
    }

    public function messages(){
        return [
            'comment.required' => '评价必须',
            'name.required' => '评价人名称必须',
            'phone.required' => '评价人联系电话必须',
            'star_scores.required' => '星级评分必须',
        ];
    }
}
