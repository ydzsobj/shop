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
        return config('global_order_prefix'). date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
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
        $start_date = Carbon::parse(Carbon::now()->subDays(30))->startOfDay()->toDateTimeString();
        $end_date = Carbon::parse(Carbon::now())->endOfDay()->toDateTimeString();

        return [$start_date, $end_date];
    }
}

/**
 * @获取api数据
 */
if(!function_exists('get_api_data')){
    function get_api_data($url,$query=[]){

        $client = new \GuzzleHttp\Client([
            'timeout'  => 2.0,
        ]);

        try {
            $response = $client->request('GET', $url,['query' => $query ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }else{
                Log::info('请求'.$url.'失败');
                return false;
            }
        }

        $result = json_decode($response->getBody());

        return $result;
    }
}

//隐藏部分手机号数字
if(!function_exists('hidden_mobile')){
    function hidden_mobile($mobile){
        return  str_repeat('*',strlen($mobile)-4).substr($mobile,-4);
    }
}

//随机四位数字
if(!function_exists('rand_mobile')){
    function rand_mobile($len = 4){
        return str_repeat('*',7).rand(1000, 9999);
    }
}

?>
