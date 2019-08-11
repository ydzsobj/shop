<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_attribute_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('good_attribute_id')->comment('关联属性表ID');
            $table->integer('remote_id')->comment('接口返回的属性值表id');
            $table->string('name')->comment('属性名称');
            $table->string('thumb_url')->nullable()->comment('属性缩略图');
            $table->tinyInteger('is_show')->default(1)->comment('是否显示，1显示 0不显示');
            $table->timestamps();

            $table->index(['good_attribute_id','remote_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('good_attribute_values');
    }
}
