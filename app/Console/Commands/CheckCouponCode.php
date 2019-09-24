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
        $codes = CouponCode::where('status', CouponCode::STATUS_RUNNING)->get();

        $successed = 0;
        $failed = 0;

        foreach ($codes as $code){

            $start_date = Carbon::parse($code->start_date);
            $end_date = Carbon::parse($code->end_date)->endOfDay();
            $now = Carbon::now();

            if($now >= $end_date){
                //失效了
                $status = CouponCode::STATUS_FINISHED;

                $code->status = $status;
                $result = $code->save();

                if($result){
                    $successed++;
                    echo '优惠码id='.$code->id. '设置状态为：已失效';
                    echo "\n";
                }else{
                    $failed++;
                }
            }
        }

        echo '共'.count($codes).' 条数据待处理；成功:'.$successed.'条,失败'.$failed.'.条'."\n";
    }
}
