<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku_id')->comment('产品sku');
            $table->integer('good_id')->comment('商品ID');
            $table->integer('s1')->comment('规格1 ID')->nullable();
            $table->string('s1_name')->comment('规格1值名称')->nullable();
            $table->integer('s2')->nullable();
            $table->string('s2_name')->nullable();
            $table->integer('s3')->nullable();
            $table->string('s3_name')->nullable();
            $table->decimal('price')->comment('实际价格');
            $table->integer('stock')->comment('库存');
            $table->string('thumb_url')->comment('缩略图');
            $table->dateTime('disabled_at')->nullable()->comment('禁用时间');
            $table->timestamps();

            $table->index('good_id');
            $table->index('sku_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('good_skus');
    }
}
