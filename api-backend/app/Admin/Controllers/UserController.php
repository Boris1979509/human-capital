<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->display(function ($item) {
            return User::TYPES_USER[$item];
        })->label();
        $grid->column('phone', __('Phone'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'))->display(function ($item) {
            return $item ? date('d.m.Y H:i', $item) : null;
        });
        $grid->column('terms_at', __('Terms at'))->display(function ($item) {
            return $item ? date('d.m.Y H:i', $item) : null;
        });
        $grid->column('created_at', __('Created at'))->display(function ($item) {
            return $item ? date('d.m.Y H:i', $item) : null;
        });

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('terms_at', __('Terms at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->number('type', __('Type'))->default(1);
        $form->mobile('phone', __('Phone'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('terms_at', __('Terms at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
