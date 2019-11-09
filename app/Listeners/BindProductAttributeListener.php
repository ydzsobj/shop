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
//     public function handle(BindProductAttributeEvent $event)
//     {
//         $good = $event->good;


// //        $sku_datas = [
// //            [
// //                'sku_id' => 1001,
// //                'attrs' => [['id'=>1,'name'=>'红'],['id'=>4,'name'=>'大']],
// //                'price' => 100,
// //                'stock' => 0
// //            ],
// //
// //            [
// //                'sku_id' => 1002,
// //                'attrs' => [['id'=>2,'name'=>'黄'],['id'=>4,'name'=>'大']],
// //                'price' => 200,
// //                'stock' => 0
// //            ],
// //            [
// //                'sku_id' => 1003,
// //                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>4,'name'=>'大']],
// //                'price' => 300,
// //                'stock' => 0
// //            ],
// //            [
// //                'sku_id' => 2001,
// //                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>4,'name'=>'大']],
// //                'price' => 300,
// //                'stock' => 0
// //            ],
// //            [
// //                'sku_id' => 3001,
// //                'attrs' => [['id'=>3,'name'=>'蓝'],['id'=>5,'name'=>'小']],
// //                'price' => 300,
// //                'stock' => 0
// //            ],
// //        ];

//         //获取产品sku
//         $get_sku_url = env('ERP_API_DOMAIN').'/api/product/'.$good->product_id;
//         $result = get_api_data($get_sku_url);
//         if(!$result){
//             return false;
//         }
//         $result_data = $result->data;

//         $sku_datas = $result_data->skus;

//         foreach ($sku_datas as $sku_data){

//             $tmp = [];
//             if($sku_data->sku_values){
//                 foreach ($sku_data->sku_values as $key=>$attr_value){
//                     $k = 's'.intval($key + 1);
//                     $tmp[$k] = $attr_value->attr_value_id;
//                     $tmp[$k.'_name'] = $attr_value->attr_value_name;
//                 }
//             }

//             $insert_data = array_merge($tmp,[
//                     'sku_id' => $sku_data->sku_code,
//                     'price' => $sku_data->sku_price,
//                     'stock' => 9999,
//                     'thumb_url' => env('ERP_API_DOMAIN','') . $sku_data->sku_image,
//                 ]
//             );

//             $sku_obj = $good->skus()->create($insert_data);
//         }
//         //获取产品属性
//         $product_attrs = $result_data->product_attr;

//         //添加产品名称
//         Product::updateOrCreate([
//             'id' => $good->product_id
//         ],[
//             'name' => $result_data->product_name,
//             'english_name' => $result_data->product_english,
//         ]);

//         if($product_attrs){
//             foreach ($product_attrs as $product_attr){

//                 $attr = $product_attr->attr;

//                 $mod = ProductAttribute::updateOrCreate([
//                      'product_id' => $good->product_id,
//                      'attr_id' => $attr->id,
//                 ],[
//                     'attr_name' => $attr->attr_name,
//                     'show_name' => $attr->attr_name
//                  ]
//                  );

//                 foreach ($product_attr->attr_values as $item){
//                     ProductAttributeValue::updateOrCreate([
//                         'product_attribute_id' => $mod->id,
//                         'attr_value_id' => $item->attr_value_id
//                     ],[
//                         'attr_value_name' => $item->attr_value_name,
//                         'english_name' => $item->attr_value_english ?? '',
//                         'show_name' => $item->attr_value_name,
//                         'thumb_url' =>  null,
//                     ]);
//                 }
//             }
//         }

//     }

    public function handle(BindProductAttributeEvent $event)
    {
        $good = $event->good;

        //获取产品sku
        $result_data = Product::with(['skus.attr_values'])
            ->where('id', $good->product_id)
            ->first();

        if(!$result_data){
            return false;
        }

        $sku_datas = $result_data->skus;

        foreach ($sku_datas as $sku_data){

            $tmp = [];
            if($sku_data->attr_values){
                foreach ($sku_data->attr_values as $key=>$attr_value){
                    $k = 's'.intval($key + 1);
                    $tmp[$k] = $attr_value->attr_value_id;
                    $tmp[$k.'_name'] = $attr_value->attr_value_name;
                }
            }

            $insert_data = array_merge($tmp,[
                    'sku_id' => $sku_data->sku_code,
                    // 'price' => $sku_data->sku_price,
                    'stock' => 9999,
                    'thumb_url' =>  $sku_data->sku_image,
                ]
            );

            $sku_obj = $good->skus()->create($insert_data);
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
