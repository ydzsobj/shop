<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePhone extends Model
{
    use SoftDeletes;

    protected $tables = 'service_phones';

    protected $fillable = [
        'name',
        'phone',
        'area_code',
        'round',
        'disabled_at'
    ];

    public static function round_phone($order_id){

        $service_phones = self::where('disabled_at', '>',0)->where('round','>',0)->get();

        if(!$service_phones){
            return false;
        }

        if($service_phones->count() == 1){
            $s_phone = $service_phones->first();
            return $s_phone->area_code. $s_phone->phone;
        }else{
            //多个手机号时 计算应该发给谁
            $cnt = $service_phones->count();
            $round = intval($order_id%$cnt + 1);//取模
            $s_phone = self::where('round', $round)->where('disabled_at','>',0)->first();

            if(!$s_phone){
                $s_phone = self::where('round', '1')->where('disabled_at','>',0)->first();
            }
            return $s_phone->area_code. $s_phone->phone;
        }
    }

    /**
     * 检查有没有可用的
     */
    public static function check_available(){
        return self::where('disabled_at','>',0)->where('round','>',0)->count();
    }
}
