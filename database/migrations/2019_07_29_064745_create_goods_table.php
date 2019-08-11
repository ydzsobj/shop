<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->comment('网页标题名');
            $table->string('name', 255)->unique()->comment('单品名');
            $table->decimal('original_price',10,2)->comment('原价');
            $table->decimal('price',10,2)->comment('单品价格');
            $table->integer('product_id')->comment('产品ID');
            $table->string('product_name',255)->nullable()->comment('产品名称');
            $table->integer('admin_user_id')->comment('发布者ID');
            $table->integer('category_id')->comment('单品类型ID');
            $table->integer('good_module_id')->comment('单品所属模块')->nullable();
            $table->string('pay_types',255)->comment('支付方式');
            $table->tinyInteger('show_comment')->default(0)->comment('是否显示评价模块，0不显示，1显示');
            $table->text('detail_desc')->comment('商品详情描述');
            $table->text('size_desc')->comment('商品规格描述');
            $table->string('main_image_url',255)->comment('封面主图');
            $table->string('main_video_url',255)->nullable()->comment('封面视频');
            $table->dateTime('deleted_at')->nullable()->comment('禁用');
            $table->timestamps();

            $table->unique('name');
            $table->index('title');
            $table->index('admin_user_id');
            $table->index('product_id');
            $table->index('category_id');
            $table->index('good_module_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
