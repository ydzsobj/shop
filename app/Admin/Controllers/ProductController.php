<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Good;
use App\Models\GoodSku;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductSku;
use App\Models\ProductSkuAttrValue;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $product = new Product();

        list($products, $search) = $product->get_data($request);

        return view('admin.product.index', compact('products', 'search'));

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

        $req = $request->only('name','english_name');

        $product = Product::create($req);

        $this->create_product_attribute($product, $request);

        list($success, $message) = $this->create_product_sku($product, $request);

        $msg = $success ? '添加成功':$message;
        $alert_type = $success ? 'success':'error';

        return redirect()->route('products.index')->with($alert_type, $msg);
    }

    public function create_product_attribute($product, $request){

        // dd($request->all());

        $product_attr = $request->post('product_attr');

        foreach($product_attr as  $attr_id=>$attr_values){

            $attr = Attribute::find($attr_id);

            $product_attribute_mod = ProductAttribute::firstOrCreate([
                'product_id' => $product->id,
                'attr_id' => $attr_id,
            ],[
                'attr_name' => $attr->name
            ]);

            foreach($attr_values as $attr_value_id=>$attr_value){
                $attr_value = AttributeValue::find($attr_value_id);
                ProductAttributeValue::firstOrCreate([
                    'product_attribute_id' => $product_attribute_mod->id,
                    'attr_value_id' => $attr_value_id,
                ],[
                    'attr_value_name' => $attr_value->name,
                    'english_name' => $attr_value->english_name
                ]);
            }
        }
    }

    public function create_product_sku($product, $request){

        $skus = $request->skus;

        foreach($skus as $sku){

            if(ProductSku::check_sku_code($sku['sku_code'] ,$product)){
                return [false, $sku['sku_code']. ' 此SKU编码已经有商品绑定,请重新编辑'];
            }

            if(isset($sku['sku_image'])){
                $sku_image = $this->upload($sku['sku_image']);
            }

            $product_sku_mod = ProductSku::firstOrCreate([
                'product_id' => $product->id,
                'sku_code' => $sku['sku_code'],
            ],[
                'attr_value_names' => $sku['attr_value_names'],
                'sku_image' => $sku_image ?? null,
            ]);

            $good_sku_data = collect([]);
            $sku_value_ids  = explode(',', $sku['attr_value_ids']);
            foreach($sku_value_ids as $key=>$attr_value_id){
                $attr_value = AttributeValue::find($attr_value_id);
                ProductSkuAttrValue::firstOrCreate([
                    'product_sku_id' => $product_sku_mod->id,
                    'attr_value_id' => $attr_value_id,
                ],[
                    'attr_value_name' => $attr_value->name
                ]);

                $key = $key + 1;
                $good_sku_data->put('s'.$key,  $attr_value_id);
                $good_sku_data->put('s'.$key.'_name', $attr_value->name);
            }

            $good_sku_data->put('price', 0);
            $good_sku_data->put('stock', 9999);

            // dump($good_sku_data->all());

            $good_ids = Good::where('product_id', $product->id)->pluck('id');

            // dd($good_ids);

            foreach($good_ids as $good_id){

                GoodSku::firstOrCreate([
                        'good_id' => $good_id,
                        'sku_id' => $sku['sku_code']
                    ],$good_sku_data->all()
                );
            }
        }

        return [true, ''];
    }

    public function edit(Request $request, $id){

        $attributes = Attribute::with('attr_values')->get();

        $format_attr_values = collect([]);
        $attributes->map(function($item) use ($format_attr_values){
            return $format_attr_values->put($item->id, $item->attr_values);
        });

        $detail = Product::with(['attrs.attribute_values','skus.attr_values'])->where('id', $id)->first();

        $formart_attr_value_ids = [];
        foreach($detail->attrs as $attr){
            $attribute_values = $attr->attribute_values;
            foreach($attribute_values as $attr_value){
                array_push($formart_attr_value_ids, $attr_value->attr_value_id);
            }
        }

        $formart_attr_value_ids = json_encode($formart_attr_value_ids);

        // dd($formart_attr_value_ids);

        $formart_skus = collect([]);

        $detail->skus->map(function($item) use ($formart_skus){
            $ids = $item->attr_values->pluck('attr_value_id');
            $format_ids = AttributeValue::whereIn('id', $ids)->orderBy('attribute_id', 'asc')->pluck('id');
            return $formart_skus->put($format_ids->implode(',') , collect(['skuPrice' => $item->sku_code, 'skuStock' => $item->sku_image]));
        });

        // dd( $formart_skus);

        return view('admin.product.edit', compact('detail', 'attributes', 'format_attr_values', 'formart_attr_value_ids', 'formart_skus'));
    }

    public function update(Request $request, $id){

        // dd($request->all());

        $product = Product::find($id);

        $product->name = $request->post('name');
        $product->english_name = $request->post('english_name');
        $product->save();

        //清除属性值和属性关系
        // $product_attr_ids = $product->attrs->pluck('id');
        // ProductAttributeValue::whereIn('product_attribute_id', $product_attr_ids)->delete();
        // ProductAttribute::whereIn('id', $product_attr_ids)->delete();

        //创建属性
        $this->create_product_attribute($product, $request);

        DB::beginTransaction();//开始事务
        //解除sku关系
        $product_sku_ids = $product->skus->pluck('id');
        ProductSkuAttrValue::whereIn('product_sku_id', $product_sku_ids)->delete();
        ProductSku::whereIn('id', $product_sku_ids)->delete();
        //创建sku
        list($success, $message) = ($this->create_product_sku($product, $request));

        if($success){
            DB::commit();
        }else{
            DB::rollBack();
        }

        $msg = $success ? '成功':$message;
        $alert_type = $success ? 'success':'error';

        return redirect()->route('products.index')->with($alert_type, $msg);

    }

    public function select_products(Request $request){

        $product = new product();
        list($products, $search)  =  $product->get_data($request);
        return returned(true, '', $products);
    }

    public function destroy(Request $request, $id){

        $res = Product::where('id', $id)->delete();

        $msg = $res ? trans('common.set.success') : trans('common.set.fail');

        return response()->json(['success' => $res, 'msg' => $msg ]);
    }

}
