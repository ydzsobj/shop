<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('good_id')->comment('商品id');
            $table->integer('remote_id')->comment('接口返回的属性表id');
            $table->string('name')->comment('属性名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('is_show')->default(1)->comment('是否显示，1显示 0不显示');
            $table->timestamps();

            $table->unique(['good_id','remote_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('good_attributes');
    }
}
