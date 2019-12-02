<?php

namespace App\Jobs;

use App\Models\smsAPI;
use App\Models\ServicePhone;
use App\Models\GoodOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class sendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GoodOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $send_msg = sprintf("ID: %d, Ada pesanan baru masuk, silakan proses", $this->order->id);
        $phone = ServicePhone::round_phone($this->order->id);

        if($phone){
            $sms = new smsAPI();
            $result = $sms->send($send_msg, $phone);

            if($result->code == 0){
                $success_msg = sprintf('发送短信成功，对应订单id=%s', $this->order->id);
                Log::info($success_msg);
                echo '发送成功';
            }else{
                $error_msg = sprintf('发送失败，错误code:%d,error:%s', $result->code, $result->error);
                Log::info($error_msg);
                echo $error_msg;
            }
        }

    }
}
