<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attribute;

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

        $attributes = Attribute::pluck('name','id');

        return view('admin.product.create', compact('attributes'));
    }

    public function store(Request $request){

    }


}
