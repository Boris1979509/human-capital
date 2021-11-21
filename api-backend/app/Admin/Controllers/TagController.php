<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TagController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tag';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->display(function () {
            return $this->type ? Tag::TYPES[$this->type] : null;
        })->label();
        $grid->column('tag', __('Tag'));
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
        $show = new Show(Tag::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'))->as(function ($status) {
            return Tag::TYPES[$status];
        });
        $show->field('tag', __('Tag'));
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
        $form = new Form(new Tag());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->select('type', __('Type'))->options(Tag::TYPES)->default(1);
        $form->text('tag', __('Tag'));
        $form->text('alternative', __('Alternative'));
        $form->switch('approved', __('Approved'))->default(1);
        $form->number('sort', __('Sort'));

        return $form;
    }
}
