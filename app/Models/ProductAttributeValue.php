<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    //
    protected $table = 'product_attribute_values';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'product_attribute_id',
        'attr_value_id',
        'attr_value_name',
        'thumb_url',
        'show_name',
        'english_name',
    ];

    /**
     * @param null $attr_value_id
     * @return string
     */
    static function get_show_name($good_id, $attr_value_ids=[]){

        $gd = Good::withTrashed()->find($good_id);
        $product_attr_ids = ProductAttribute::where('product_id', $gd->product_id)->pluck('id');

        $show_name_str = '';

        foreach ($attr_value_ids as $attr_value_id){
            if($attr_value_id){
                $first =  self::where('attr_value_id', $attr_value_id)
                    ->whereIn('product_attribute_id', $product_attr_ids)
                    ->first();
                $show_name_str .= $first ? $first->show_name.'-' : '';
            }
        }

        return rtrim($show_name_str, '-');
    }

    /**
     * @param $good_id
     * @param array $attr_value_ids
     * @return string
     */
    static function get_english_name($good_id, $attr_value_ids=[]){

        $gd = Good::withTrashed()->find($good_id);
        $product_attr_ids = ProductAttribute::where('product_id', $gd->product_id)->pluck('id');

        $english_name_str = '';

        foreach ($attr_value_ids as $attr_value_id){
            if($attr_value_id){
                $first =  self::where('attr_value_id', $attr_value_id)
                    ->whereIn('product_attribute_id', $product_attr_ids)
                    ->first();
                $english_name_str .= $first ? $first->english_name.'-' : '';
            }
        }

        return rtrim($english_name_str, '-');
    }

}
