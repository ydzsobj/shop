<?php

namespace App\Console\Commands;

use App\Models\Good;
use App\Models\Product;
use Illuminate\Console\Command;

class ImportProductInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入产品的信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $product_ids = Good::pluck('product_id')->unique();

        foreach ($product_ids as $product_id){
            $get_attr_url = env('ERP_API_DOMAIN').'/api/product/'.$product_id;
            $result = get_api_data($get_attr_url);
            if(!$result){
                continue;
            }
            $result_data = $result->data;

            if($result_data){
                //添加产品名称
                $res = Product::updateOrCreate([
                    'id' => $product_id
                ],[
                    'name' => $result_data->product_name,
                    'english_name' => $result_data->product_english,
                ]);

                if($res){
                    echo '产品id='.$product_id."信息保存成功\n";
                }
            }
        }
    }
}
