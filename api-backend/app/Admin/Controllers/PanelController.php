<?php

namespace App\Admin\Controllers;

use App\Models\Dictionary;
use App\Models\UI\Panel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PanelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Panel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Panel());

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'));
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('color', __('Color'))->display(function ($color) {
            return "<span style='color:$color'>$color</span>";
        });
        $grid->column('sort', __('Sort'));
        $grid->column('vertical', __('Vertical'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
//        $grid->column('deleted_at', __('Deleted at'));

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
        $show = new Show(Panel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('color', __('Color'));
        $show->field('sort', __('Sort'));
        $show->field('vertical', __('Vertical'));
//        $show->field('created_at', __('Created at'));
//        $show->field('updated_at', __('Updated at'));
//        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Panel());

        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->select('type', __('Type'))->options(Panel::TYPES);
        $form->text('title', __('Title'));
        $form->text('description', __('Description'));
        $form->color('color', __('Color'));
        $form->number('sort', __('Sort'));
        $form->switch('vertical', __('Vertical'))->default(1);

        $form->hasMany('items', __('admin.items'), function (Form\NestedForm $form) {
            $form->text('title', __('Title'));
//            $form->text('description', __('Description'));
            $form->select('dictionary_id', __('Элемент справочника'))->options(Dictionary::pluck('option', 'id'));
            $form->number('sort', __('Sort'));
        });

        return $form;
    }
}
