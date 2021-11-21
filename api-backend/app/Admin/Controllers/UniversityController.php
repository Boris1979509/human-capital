<?php

namespace App\Admin\Controllers;

use App\Models\City;
use App\Models\University;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UniversityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'University';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new University());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->column('id', __('Id'));
        $grid->column('city.name', __('City id'))->label('default');
        $grid->column('title', __('Title'));
        $grid->column('alternative', __('Alternative'));
        $grid->column('approved', __('Approved'))->switch();
        $grid->column('sort', __('Sort'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(University::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('city_id', __('City id'));
        $show->field('title', __('Title'));
        $show->field('alternative', __('Alternative'));
        $show->field('approved', __('Approved'));
        $show->field('sort', __('Sort'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new University());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->select('city_id', __('City'))->options(City::pluck('name', 'id'));
        $form->text('title', __('Title'));
        $form->text('alternative', __('Alternative'));
        $form->switch('approved', __('Approved'))->default(1);
        $form->number('sort', __('Sort'));

        return $form;
    }
}
