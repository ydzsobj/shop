<?php

namespace App\Admin\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;

class AttributeValueController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('属性值管理')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param int     $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('添加属性值')
            ->description('创建')
            ->body($this->form());
    }

    public function grid()
    {
        $grid = new Grid(new AttributeValue());

        $grid->id('ID');
        $grid->name('属性值名称');
        $grid->english_name('英文名称');
        $grid->column('attribute.name', '所属属性');

        // $grid->column('image_url','图标')->image(env('APP_URL').'/uploads/admin/', 100, 100);

        // $grid->sort('排序');

        $grid->created_at('创建时间');
//        $grid->updated_at();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name');
        });

        $grid->model()->orderBy('attribute_id', 'desc');

        return $grid;
    }

    public function form()
    {
        $form = new Form(new AttributeValue());

//        $form->display('id', 'ID');
//        $form->text('name','类别名称')->rules('required');

        $form->text('name','名称')
            ->creationRules(['required', "unique:attribute_values"])
            ->updateRules(['required', "unique:attribute_values,name,{{id}}"]);

        $form->text('english_name','英文名称');
            // ->creationRules(['required'])
            // ->updateRules(['required']);


        $form->select('attribute_id','所属属性')->options(Attribute::pluck('name','id'));

        // $form->text('sort','排序（范围0-99）')->rules('required|regex:/^\d{1,2}$/|min:1', [
        //     'regex' => '范围0-99',
        //     'min'   => '不能少于1个字符',
        // ]);

        // $form->image('image_url', '图标')->uniqueName()->rules('required');

//        $form->display('created_at');
//        $form->display('updated_at');

        return $form;
    }
}
