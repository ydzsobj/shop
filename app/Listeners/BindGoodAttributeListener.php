<?php

namespace App\Listeners;

use App\Events\BindGoodAttributeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;


class BindGoodAttributeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BindGoodAttributeEvent  $event
     * @return void
     */
    public function handle(BindGoodAttributeEvent $event)
    {
        $good = $event->good;


//        $sku_datas = [
//            [
//                'sku_id' => 1001,
//                'attrs' => [['id'=>1,'name'=>'红'],['id'=>4,'name'=>'大']],
//                'price' => 100,
//                'stock' => 0
//            ],
//
//            [
//                'sku_id' => 1002,
//                'attrs' => [['id'=>2,'name'=>'黄'],['id'=>4,'name'=>'大']],
//                'price' => 200,
//                'stock' => 0
//            ],
//            [
//                'sku_id' => 1003,
//                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>4,'name'=>'大']],
//                'price' => 300,
//                'stock' => 0
//            ],
//            [
//                'sku_id' => 2001,
//                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>4,'name'=>'大']],
//                'price' => 300,
//                'stock' => 0
//            ],
//            [
//                'sku_id' => 3001,
//                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>5,'name'=>'小']],
//                'price' => 300,
//                'stock' => 0
//            ],
//        ];

        //获取产品sku
        $get_sku_url = env('ERP_API_DOMAIN').'/api/product/sku/'.$good->product_id;
        $result_data = $this->get_api_data($get_sku_url);
        $sku_datas = collect([]);

        foreach ($result_data as $item){

            $sku_datas->push([
                'sku_id' => $item->sku_id,
                'attrs' => unserialize($item->sku_value),
                'price' => $item->sku_price,
                'stock' => 9999,
                'thumb_url' => env('ERP_API_DOMAIN',''). $item->sku_image
            ]);
        }

        foreach ($sku_datas->all() as $sku_data){

            $tmp = [];
            if($sku_data['attrs']){
                foreach ($sku_data['attrs'] as $key=>$attr){
                    $k = 's'.intval($key);
                    $tmp[$k] = $attr['sku_value_id'];
                    $tmp[$k.'_name'] = $attr['sku_value_name'];
                }
            }

            $insert_data = array_merge($tmp,[
                    'sku_id' => $sku_data['sku_id'],
                    'price' => $sku_data['price'],
                    'stock' => $sku_data['stock'],
                    'thumb_url' => $sku_data['thumb_url'],
                ]
            );

            $sku_obj = $good->skus()->create($insert_data);
        }

//        $attr_data = [
//            [
//                'id' => 1,
//                'name' => '颜色',
//                'value' => [['name'=>'红','id'=>1],['name'=>'黄','id'=>2],['name'=>'蓝','id'=>3]],
//            ],
//
//            [
//                'id' => 2,
//                'name' => '尺寸',
//                'value' => [['name'=> '大','id'=>4],['name' => '小', 'id' =>5]],
//            ]
//        ];

       //获取产品属性
        $get_attr_url = env('ERP_API_DOMAIN').'/api/product/'.$good->product_id;
        $result_data = $this->get_api_data($get_attr_url);
        $attr_data = unserialize($result_data->spec_value);

        if($attr_data){
            foreach ($attr_data as $data){

                $attr_obj = $good->attributes()->create([
                    'remote_id' => $data['attr_id'],
                    'name' => $data['attr_name']
                ]);

                $values = collect([]);

                foreach ($data['attr_value'] as $item){
                    $values->push([
                        'remote_id' => $item['attr_value_id'],
                        'name' => $item['attr_value_name'],
                        'thumb_url' => isset($item['attr_value_image']) ? env('ERP_API_DOMAIN').$item['attr_value_image'] : null,
                    ]);
                }

                $attr_obj->attribute_values()->createMany($values->all());
            }
        }

    }


    /**
     * @param $good
     * @param $url
     * @return bool
     */
    protected function get_api_data($url){

        $client = new Client();

        try {
            $response = $client->request('GET', $url);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }else{
                Log::info('请求'.$url.'失败');
                return false;
            }
        }

        $result = json_decode($response->getBody());

        $result_data = $result->data;

        return $result_data;
    }
}
