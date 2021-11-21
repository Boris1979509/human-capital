<?php

namespace App\Admin\Controllers;

use App\Helpers\ImageHelper;
use App\Models\City;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class CityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Города';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new City());

        $grid->column('id', __('Id'));
        $grid->column('sort', __('Sort'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('description', __('field.description'))->display(function ($item) {
            return mb_strimwidth($item, 0, 500, '...');
        });
        $grid->column('files', __('field.images'))->display(function () {
            $images = array();
            foreach ($this->files as $file) {
                array_push($images, preg_replace("/images\//", "images/small.", $file->file));
            }
            return $images;
        })->carousel(150, 100);

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
        $show = new Show(City::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sort', __('Sort'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('description', __('Description'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new City());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->text('name', __('field.name'));
        $form->text('slug', __('field.slug'));
        $form->text('description', __('field.description'));
        $form->number('sort', __('field.sort'))->default(0);
        $form->hasMany('files', __('field.images'), function (Form\NestedForm $form) {
            $form->image('file', __('field.image'))
                ->options(['showClose'=>false])
                ->options(['fileActionSettings'=>['showRemove'=>true]])
                ->options(['otherActionButtons'=>ImageHelper::previewRotateButtons()])
                ->uniqueName();
            $form->number('sort', __('field.sort'))->default(0);
        });
        $form->saving(function (Form $form) {
            if ($form->slug === null) {
                $form->slug = Str::slug($form->option);
            }
        });
        return $form;
    }
}
