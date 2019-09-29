<?php

namespace App\Admin\Controllers;

use App\Http\Requests\StoreCouponCode;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\RuleFixed;
use App\Models\RuleFullReduction;
use App\Models\RulePercent;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponCodeController extends BaseController
{
    public function index(Request $request){

        $cop = new CouponCode();

        list($search, $coupon_codes) = $cop->get_date($request);

        $type_list = config('coupon.type_list');
        $status_list = config('coupon.status');
        $apply_type_list = config('coupon.apply_type_list');
        return view('admin.coupon_code.index',
            compact(
                'coupon_codes',
                'search',
                'type_list',
                'apply_type_list',
                'status_list'
            )
        );
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        $type_list = config('coupon.type_list');
        $apply_type_list = config('coupon.apply_type_list');
       return view('admin.coupon_code.create',
           compact(
               'type_list',
               'apply_type_list'
           )
       );
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreCouponCode $request){

        $req = $request->only('code', 'type_id','apply_type_id','good_id');

        $type_id = $request->post('type_id');

        $rule_data = collect([]);

        switch ($type_id){

            case CouponCode::TYPE_PERCENT:
                $targetable_type = 'rule_percents';
                $rule_data->put('percent', $request->post('percent'));
                $mod = RulePercent::create($rule_data->all());
                break;

            case CouponCode::TYPE_FIXED:
                $targetable_type = 'rule_fixed';
                $rule_data->put('money', $request->post('fixed_money'));
                $mod = RuleFixed::create($rule_data->all());
                break;

            case CouponCode::TYPE_FULL_REDUCTION:
                $targetable_type = 'rule_full_reductions';
                $rule_data = $request->post('full_reduction');
                $mod = RuleFullReduction::create($rule_data);
                break;

            default:
                return redirect(route('coupon_codes.index'))->with('error', trans('common.create.fail'));
        }

        $targetable_id = $mod ? $mod->id : false;

        $admin_user = Admin::user();

        $start_date = Carbon::parse($request->post('start_date'));
        $end_date = Carbon::parse($request->post('end_date'))->endOfDay();
        $now = Carbon::now();

        if($now->lt($start_date)){
            //未开始
            $status = CouponCode::STATUS_NO_START;
        }elseif(Carbon::parse($now)->between($start_date, $end_date)){
            //执行中
            $status = CouponCode::STATUS_RUNNING;
        }else{
            //失效了
            $status = CouponCode::STATUS_FINISHED;
        }

        //追加数据
        $append_data = [
            'admin_user_id' => $admin_user->id,
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        if($targetable_id){
            $result = CouponCode::create(array_merge($req, $append_data, compact('targetable_id','targetable_type')));

            if($result){
                return redirect(route('coupon_codes.index'))->with('success', trans('common.create.success'));
            }else{
                return redirect(route('coupon_codes.index'))->with('error', trans('common.create.fail'));
            }
        }else{
            return redirect(route('coupon_codes.index'))->with('error', trans('common.create.fail'));
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id){

        $cop = CouponCode::find($id);

        $res = $cop->delete();

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg ]);
    }
}
