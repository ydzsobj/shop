<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTraceLogRequest extends FormRequest
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
            'device' => 'required',
//            'referer_url' => 'required',
            'access_url' => 'required',
        ];
    }

    public function messages(){
        return [
            'device.required' => '设备信息必须',
//            'referer_url.required' => '来源地址必须',
            'access_url.required' => '访问地址必须',
        ];
    }
}
