<?php

namespace App\Listeners;

use App\Events\BindProductAttributeEvent;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BindProductAttributeListener
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
     * @param  BindProductAttributeEvent  $event
     * @return void
     */
    public function handle(BindProductAttributeEvent $event)
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
        $result = get_api_data($get_sku_url);
        if(!$result){
            return false;
        }
        $result_data = $result->data;
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
        $result = get_api_data($get_attr_url);
        if(!$result){
            return false;
        }
        $result_data = $result->data;
        $attr_data = unserialize($result_data->spec_value);

        //添加产品名称
        Product::updateOrCreate([
            'id' => $good->product_id
        ],[
            'name' => $result_data->product_name,
            'english_name' => $result_data->product_english,
        ]);

        if($attr_data){
            foreach ($attr_data as $data){

                 $product_attr = ProductAttribute::updateOrCreate([
                     'product_id' => $good->product_id,
                     'attr_id' => $data['attr_id'],
                ],[
                    'attr_name' => $data['attr_name'],
                    'show_name' => $data['attr_name']
                 ]
                 );

                foreach ($data['attr_value'] as $item){
                    ProductAttributeValue::updateOrCreate([
                        'product_attribute_id' => $product_attr->id,
                        'attr_value_id' => $item['attr_value_id']
                    ],[
                        'attr_value_name' => $item['attr_value_name'],
                        'english_name' => $item['attr_value_english'] ?? '',
                        'show_name' => $item['attr_value_name'],
                        'thumb_url' => isset($item['attr_value_image']) ? env('ERP_API_DOMAIN').$item['attr_value_image'] : null,
                    ]);
                }
            }
        }

    }


//    /**
//     * @param $good
//     * @param $url
//     * @return bool
//     */
//    protected function get_api_data($url){
//
//        $client = new Client();
//
//        try {
//            $response = $client->request('GET', $url);
//        } catch (RequestException $e) {
//            if ($e->hasResponse()) {
//                echo $e->getResponse();
//            }else{
//                Log::info('请求'.$url.'失败');
//                return false;
//            }
//        }
//
//        $result = json_decode($response->getBody());
//
//        $result_data = $result->data;
//
//        return $result_data;
//    }
}
