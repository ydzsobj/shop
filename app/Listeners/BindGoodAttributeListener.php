<?php

namespace App\Listeners;

use App\Events\BindGoodAttributeEvent;
use App\Models\GoodAttribute;
use App\Models\GoodAttributeValue;
use App\Models\Product;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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

         //获取产品sku
         $result_data = Product::with(['skus.attr_values'])
            ->where('id', $good->product_id)
            ->first();

        if(!$result_data){
            return false;
        }

        //获取产品属性
        $product_attrs = $result_data->attrs;

        if($product_attrs){
            foreach ($product_attrs as $product_attr){

                $mod = GoodAttribute::updateOrCreate([
                     'good_id' => $good->id,
                     'attr_id' => $product_attr->attr_id,
                ],[
                    'attr_name' => $product_attr->attr_name,
                    'show_name' => $product_attr->attr_name
                 ]
                 );

                foreach ($product_attr->attribute_values as $item){
                    GoodAttributeValue::updateOrCreate([
                        'good_attribute_id' => $mod->id,
                        'attr_value_id' => $item->attr_value_id
                    ],[
                        'attr_value_name' => $item->attr_value_name,
                        'show_name' => $item->attr_value_name,
                        'thumb_url' =>  null,
                    ]);
                }
            }
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

}
