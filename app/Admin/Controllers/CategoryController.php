<?php

namespace App\Admin\Controllers;

use App\Models\GoodCategory;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Arr;

class CategoryController
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
            ->header('类别管理')
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
            ->header('类别编辑')
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
            ->header('添加商品类别')
            ->description('创建')
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Config')
            ->description('detail')
            ->body(Admin::show(GoodCategory::findOrFail($id), function (Show $show) {
                $show->id();
                $show->name();
                $show->created_at();
                $show->updated_at();
            }));
    }

    public function grid()
    {
        $grid = new Grid(new GoodCategory());

        $country_list = config('country.country_list');
        $options = collect($country_list)->pluck('name', 'id');

        $grid->id('ID');
        $grid->column('country_id','所属国家')->display(function ($country_id) use ($country_list) {

            $country = Arr::get($country_list, $country_id);
            return $country['name'];

        });
        $grid->name('类别名称');
        $grid->show_name('展示名称');

        $grid->column('image_url','图标')->image(env('APP_URL').'/uploads/admin/', 100, 100);

        $grid->sort('排序');

        $grid->created_at('创建时间');
//        $grid->updated_at();

        $grid->model()->orderBy('country_id', 'desc');

        $grid->filter(function ($filter) use ($options) {
            $filter->disableIdFilter();
            $filter->equal('country_id','所属国家')->select($options);
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form(new GoodCategory());

//        $form->display('id', 'ID');
//        $form->text('name','类别名称')->rules('required');

        $country_list = config('country.country_list');

        $options = collect($country_list)->pluck('name', 'id');

        $form->select('country_id', '所属国家')->rules('required')->options($options);

        $form->text('name','类别名称')
            ->creationRules(['required', "unique:good_categories"])
            ->updateRules(['required', "unique:good_categories,name,{{id}}"]);

        $form->text('show_name','展示名称')
            ->creationRules(['required'])
            ->updateRules(['required']);

        $form->text('sort','排序（范围0-99）')->rules('required|regex:/^\d{1,2}$/|min:1', [
            'regex' => '范围0-99',
            'min'   => '不能少于1个字符',
        ]);

        $form->image('image_url', '图标')->uniqueName()->rules('required');

//        $form->display('created_at');
//        $form->display('updated_at');

        return $form;
    }
}
