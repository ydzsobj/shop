<?php
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

if (!function_exists('getSql')) {
    function getSql ()
    {
        DB::listen(function ($sql) {
//            dump($sql);
            $singleSql = $sql->sql;
            if ($sql->bindings) {
                foreach ($sql->bindings as $replace) {
                    $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                    $singleSql = preg_replace('/\?/', $value, $singleSql, 1);
                }
                dump($singleSql);
            } else {
                dump($singleSql);
            }
        });

    }
}

/**
 * @生成order_sn
 */
if(!function_exists('generate_sn')){
    function generate_sn(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}

/**
 * @api返回json
 */
if(!function_exists('returned')){
    function returned($success, $msg,$data=[]){
        return response()->json(['success' => $success, 'msg' => $msg,'data' => $data ]);
    }
}

if(!function_exists('recent_thirty_days')){
    function recent_thirty_days(){
        $start_date = Carbon::parse(Carbon::now()->subDays(30))->startOfDay();
        $end_date = Carbon::parse(Carbon::now())->endOfDay();

        return [$start_date, $end_date];
    }
}

?>