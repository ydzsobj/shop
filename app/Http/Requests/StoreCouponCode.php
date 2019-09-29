<?php

namespace App\Http\Requests;

use App\Models\CouponCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponCode extends FormRequest
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
            'code' =>[
                'required',
                'max:255',
                Rule::unique('coupon_codes')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'type_id' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

    public function messages(){
        return [
            'code.unique' => '优惠码已经存在',
            'percent.between' => '百分比输入范围1-99',
            'percent.required' => '百分比不能为空',
            'fixed_money.required' => '固定金额不能为空',
            'full_reduction.amount.required' => '满减数量不能为空',
            'full_reduction.money.required' => '满减金额不能为空',
            'good_id.required' => '选择商品不能为空',
        ];
    }

    /**
     *  配置验证器实例。
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $validator->sometimes('percent', 'required|numeric|between:1,99', function ($input) {
                return $input->type_id == CouponCode::TYPE_PERCENT;
            });

            $validator->sometimes('fixed_money', 'required|numeric', function ($input) {
                return $input->type_id == CouponCode::TYPE_FIXED;
            });

            $validator->sometimes(['full_reduction.amount','full_reduction.money'], 'required|numeric', function ($input) {
                return $input->type_id == CouponCode::TYPE_FULL_REDUCTION;
            });

            $validator->sometimes('good_id', 'required|integer', function ($input) {
                return $input->apply_type_id == CouponCode::APPLY_TYPE_GOOD;
            });

        });
    }
}
