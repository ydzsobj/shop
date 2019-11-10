<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductSku;

class ProductSkuController extends Controller
{

    public function check_sku_exist(Request $request){

        $sku_code = $request->get('sku_code');

        $sku_info = ProductSku::where('sku_code', $sku_code)->first();
    }
}
