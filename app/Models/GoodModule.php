<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodModule extends Model
{
    //
    protected $table = 'good_modules';

    protected $fillable = [
        'sort',
        'name',
        'show_name',
        'image_url',
        'country_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goods(){
        return $this->hasMany(Good::class);
    }

    public function good_module_images(){
        return $this->hasMany(GoodModuleImage::class);
    }

    public function get_data(){

        return self::with(['goods' => function($query){
            $query->select('id','title','original_price','price','good_module_id','main_image_url')->orderBy('id', 'desc');
        }])
            ->select('id','name')
            ->orderBy('good_modules.id','desc')
            ->get();
    }
}
