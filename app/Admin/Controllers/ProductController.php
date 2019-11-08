<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductSku;
use App\Models\ProductSkuAttrValue;

class ProductController extends BaseController
{
    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $page = $request->get('page') ?: 1;
        $limit = $request->get('per_page') ?: 20;
        $keywords = $request->get('keywords');


        $url =  env('ERP_API_DOMAIN'). '/api/product';

        $search = compact('page', 'limit','keywords');

        $products = get_api_data($url, $search);

        $pages = 0;

        if($products){

            $pages = intval(ceil($products->count/$limit));
        }

        $search['per_page'] = $limit;

        return view('admin.product.index', compact('products', 'search','pages'));

    }

    public function create(){

        // $attributes = Attribute::pluck('name','id');

        $attributes = Attribute::with('attr_values')->get();

        $format_attr_values = collect([]);
        $attributes->map(function($item) use ($format_attr_values){
            return $format_attr_values->put($item->id, $item->attr_values);
        });

        return view('admin.product.create', compact('attributes','format_attr_values'));
    }

    public function store(Request $request){

        dump($request->all());

        $req = $request->only('name','english_name');

        $product = Product::create($req);

        $product_attr = $request->post('product_attr');

        foreach($product_attr as  $attr_id=>$attr_values){

            $attr = Attribute::find($attr_id);

            $product_attribute_mod = ProductAttribute::create([
                'product_id' => $product->id,
                'attr_id' => $attr_id,
                'attr_name' => $attr->name,
                'show_name' => $attr->name
            ]);

            foreach($attr_values as $attr_value_id=>$attr_value){
                $attr_value = AttributeValue::find($attr_value_id);
                ProductAttributeValue::create([
                    'product_attribute_id' => $product_attribute_mod->id,
                    'attr_value_id' => $attr_value_id,
                    'attr_value_name' => $attr_value->name,
                    'english_name' => $attr_value->english_name,
                    'show_name' => $attr_value->name
                ]);
            }
        }

        $skus = $request->post('skus');

        foreach($skus as $sku){

            if(isset($sku['sku_image'])){
                $sku_image = $this->upload($sku['sku_image']);
                dd($sku_image);
            }

            $product_sku_mod = ProductSku::create([
                'product_id' => $product->id,
                'sku_code' => $sku['sku_code'],
                'attr_value_names' => $sku['attr_value_names'],
                'sku_image' => $sku_image ?? null,
            ]);

            $sku_value_ids  = explode(',', $sku['attr_value_ids']);
            foreach($sku_value_ids as $attr_value_id){
                $attr_value = AttributeValue::find($attr_value_id);
                ProductSkuAttrValue::create([
                    'product_sku_id' => $product_sku_mod->id,
                    'attr_value_id' => $attr_value_id,
                    'attr_value_name' => $attr_value->name
                ]);
            }
        }

        $msg = $product ? '添加成功':'添加失败';
        $alert_type = $product ? 'success':'error';

        // return redirect()->route('products.index')->with($alert_type, $msg);
    }


}
