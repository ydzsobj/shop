<?php

namespace App\Models;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Good extends Model
{
    use SoftDeletes;

    protected $table = 'goods';

    protected $page_size = 20;


    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'name',
        'original_price',
        'price',
        'product_id',
        'product_name',
        'admin_user_id',
        'category_id',
        'good_module_id',
        'pay_types',
        'show_comment',
        'detail_desc',
        'size_desc',
        'main_image_url',
        'main_video_url'

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function list_images(){
        return $this->hasMany(GoodImage::class);
    }

    public function admin_user(){
        return $this->belongsTo(AdminUser::class);
    }

    public function category(){
        return $this->belongsTo(GoodCategory::class,'category_id','id')->withDefault();
    }

    public function good_module(){
        return $this->belongsTo(GoodModule::class)->withDefault();
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes(){
        return $this->hasMany(GoodAttribute::class);
    }

    public function skus(){
        return $this->hasMany(GoodSku::class);
    }

    /**
     * 列表数据
     * @param $request
     * @return array
     */
    public function get_data($request){

        $base_query =  Good::withTrashed()->with(['list_images','category','admin_user','good_module']);

        list($query, $search) = $this->query_conditions($base_query, $request);

        $data = $query->select(
                'goods.*'
            )
            ->orderBy('goods.id', 'desc')
            ->paginate($this->page_size);

        return [$data, $search];
    }

    /**
     * 条件筛选
     * @param $request
     * @param $base_query
     */
    protected function query_conditions($base_query, $request){

        //筛选时间
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        if($start_date && $end_date){
            $base_query->whereBetween('goods.created_at', [$start_date, Carbon::parse($end_date)->endOfDay()]);
        }

        //单品类型
        $category_id = $request->get('category_id');
        if($category_id){
            $base_query->where('category_id', $category_id);
        }

        //单品类型
        $good_module_id = $request->get('good_module_id');
        if($good_module_id){
            $base_query->where('good_module_id', $good_module_id);
        }

        //单品类型
        $product_id = $request->get('product_id');
        if($product_id){
            $base_query->where('product_id', $product_id);
        }

        //禁用
        $status = $request->get('status');
        switch($status){
            case 1:
                $base_query->whereNull('goods.deleted_at');
                break;
            case 2:
                $base_query->whereNotNull('goods.deleted_at');
                break;
        }

        //关键词
        $keywords = $request->get('keywords');
        if($keywords){
            $base_query->where(function($query) use ($keywords){
                $query->where('goods.title', 'like', '%'.$keywords.'%')
                    ->orWhere('goods.name','like', '%'.$keywords.'%');
            });
        }

        $admin_user = Admin::user();

        if($admin_user->isAdministrator() || $admin_user->isRole('leader')){
            //管理员 || 组长
        }else{
            //组员
            $base_query->where('goods.admin_user_id', $admin_user->id);
        }

        //分页大小
        $per_page = $request->get('per_page') ?: $this->page_size;
        $this->page_size = $per_page;


        $search = compact('start_date','end_date','category_id','product_id','status','keywords','per_page','good_module_id');

        return [$base_query, $search];
    }

    public function getPayTypesAttribute($value)
    {
        return json_decode($value);
    }

    public function getPriceAttribute($value){
        return $value;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function export($request){

        $base_query = Good::leftJoin('admin_users','admin_users.id','goods.admin_user_id');

        list($query, $search) = $this->query_conditions($base_query, $request);

        return $query->select(
            'goods.id',
            'goods.name',
            'goods.title',
            'goods.original_price',
            'goods.price',
            'goods.detail_desc',
            'admin_users.username',
            'goods.created_at',
            'goods.main_video_url'
        )
            ->orderBy('goods.id','desc')
            ->get();

    }

    /**
     * 前台获取商品列表
     * @param $request
     * @return mixed
     */
    public function user_good_data($search = []){

        $category_id = array_get($search, 'category_id', null);
        $good_module_id = array_get($search, 'good_module_id', null);

        return Good::when($category_id,function($query) use($category_id){
            $query->where('category_id', $category_id);
        })
            ->when($good_module_id, function($query) use ($good_module_id){
            $query->where('good_module_id', $good_module_id);
        })
            ->select(
                'id as goodsId',
                'title as name',
                'original_price as mallPrice',
                'price',
                'good_module_id',
                'main_image_url as image'
            )
            ->orderBy('id', 'desc')
            ->paginate($this->page_size);
    }

}
