<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckCouponCode extends FormRequest
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
            'cart_data' => 'required|array',
            'coupon_code' => 'required|string|max:255',
        ];
    }

//    public function messages(){
//        return [
//            'cart_data.required' =>  '购物车数据不能为空',
//            'cart_data.array' => '购物车数据类型不正确',
//            'receiver_name.required' => '收货人不能为空',
//            'receiver_phone.required' => '收货电话不能为空',
//            'address.required' => '收货地址不能为空',
//            'short_address.required' => '收货地址不能为空',
//        ];
//    }
}
