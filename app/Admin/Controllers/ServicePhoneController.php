<?php

namespace App\Admin\Controllers;

use App\Models\ServicePhone;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;

class ServicePhoneController
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
            ->header('电话设置')
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
            ->header('电话编辑')
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
            ->header('添加电话')
            ->description('创建')
            ->body($this->form());
    }



    public function grid()
    {
        $grid = new Grid(new ServicePhone());

        $grid->id('ID');
        $grid->name('姓名');
        $grid->phone('电话');
        $grid->area_code('区号');
        $grid->round('轮询规则');
        // $grid->column('disabled_at','启用状态')->display(function($disabled_at){
        //     return $disabled_at? '已停用':'启用中';
        // });

        $states = ['on','off'];

        $grid->column('disabled_at','状态')->switch($states);

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name');
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ServicePhone());

        $form->text('name','姓名')
        ->creationRules(['required'])
        ->updateRules(['required']);

        $form->text('phone','电话')
            ->creationRules(['required', "unique:service_phones"])
            ->updateRules(['required', "unique:service_phones,phone,{{id}}"]);

        $form->text('area_code','区号')
            ->creationRules(['required'])
            ->updateRules(['required']);

        $form->text('round','规则')
            ->creationRules(['required', "unique:service_phones"])
            ->updateRules(['required', "unique:service_phones,round,{{id}}"]);

        $form->switch('disabled_at', '启用/禁用');

        // $form->saving(function (Form $form) {

        //     // dd($form);

        //     $form->disabled_at = $form->disabled_at == 'on' ? null : Carbon::now();

        // });

//        $form->display('created_at');
//        $form->display('updated_at');

        return $form;
    }
}
