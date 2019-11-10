<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    protected $table = 'products';

    protected $page_size = 20;

    use SoftDeletes;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'english_name'

    ];

    public function attrs(){
        return $this->hasMany(ProductAttribute::class);
    }

    public function skus(){
        return $this->hasMany(ProductSku::class);
    }

    public function get_data($request){

        $per_page = $request->get('per_page');
        $keywords = $request->get('keywords');

        $per_page = $per_page ?: $this->page_size;

        $search = compact('page_size','keywords', 'per_page');

        $products = self::with(['attrs.attribute_values','skus'])
            ->ofKeywords($keywords)
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        return [$products, $search];
    }

    public function scopeOfKeywords($query, $keywords){

        if($keywords){
            return $query->where('name', 'like', '%'. $keywords.'%');
        }else{
            return $query;
        }
    }
}
