<?php

namespace App\Console\Commands;
use App\Models\CouponCode;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCouponCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:coupon_code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测优惠码是否过期，设置过期状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取所有的有效状态的优惠码
        $codes = CouponCode::whereIn('status', [CouponCode::STATUS_NO_START,CouponCode::STATUS_RUNNING])
            ->orderBy('id', 'desc')
            ->get();

        $successed = 0;
        $failed = 0;

        foreach ($codes as $code){

            $start_date = Carbon::parse($code->start_date);
            $end_date = Carbon::parse($code->end_date);
            $now = Carbon::now();

            if($now->gt($end_date)){
                //失效了
                $status = CouponCode::STATUS_FINISHED;
            }elseif(Carbon::parse($now)->between($start_date, $end_date) && $code->status != CouponCode::STATUS_RUNNING){
                //执行中
                $status = CouponCode::STATUS_RUNNING;
            }else{
                $status = null;
            }

            if($status){
                $code->status = $status;
                $result = $code->save();

                $status_list = config('coupon.status');

                if($result){
                    $successed++;
                    echo '优惠码id='.$code->id. '设置状态为:'.array_get($status_list, $status);
                    echo ' now='.$now->toDateTimeString().'; end_date='.$end_date->toDateTimeString().'; start_date='.$start_date->toDateTimeString();
                    echo "\n";
                }else{
                    $failed++;
                }
            }
        }

        echo '['.Carbon::now().']共检测'.count($codes).'条数据；成功设置:'.$successed.'条,失败'.$failed.'条'."\n";
        echo '###########################################';
        echo "\n";
    }
}
