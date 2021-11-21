<?php
namespace App\Admin\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Content;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ContentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Контент';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Content());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->column('id', __('Id'));
        $grid->column('title', __('field.title'));
        $grid->column('slug', __('field.slug'))->display(function ($slug) {
            return '<a href="/' . $slug . '.html" target="_blank">' . $slug . '</a>';
        });
        $grid->column('type', __('field.type'))->display(function () {
            return $this->type ? Content::TYPES[$this->type] : null;
        })->label();
        $grid->column('anons', __('field.anons'))->display(function ($item) {
            return mb_strimwidth($item, 0, 100, '...');
        });
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

        $grid->column('active', __('field.active'))->bool();

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
        $show = new Show(Content::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('field.title'));
        $show->field('type', __('field.type'))->as(function ($status) {
            return Content::TYPES[$status];
        });
        $show->field('anons', __('field.anons'));
        $show->field('description', __('field.description'));
        $show->field('active', __('field.active'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Content());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->text('title', __('field.title'));
        $form->text('slug', __('field.slug'));
        $form->select('type', __('field.type'))->options(Content::TYPES);
        $form->textarea('anons', __('field.anons'));
        $form->ckeditor('description', __('field.description'))->options();
        $form->text('link', __('field.video_link'));
        $form->switch('active', __('field.active'))->default(1);
        $form->hasMany('files', __('field.images'), function (Form\NestedForm $form) {
            $form->file('file', __('field.image'))
                ->options(['showClose'=>false])
                ->options(['fileActionSettings'=>['showRemove'=>true]])
                ->options(['otherActionButtons'=>ImageHelper::previewRotateButtons()])
                ->uniqueName();
            $form->number('sort', __('field.sort'))->default(0);
        });

        return $form;
    }
}
