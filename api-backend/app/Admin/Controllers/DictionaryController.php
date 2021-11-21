<?php

namespace App\Admin\Controllers;

use App\Models\Dictionary;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class DictionaryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dictionary';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Dictionary());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->display(function () {
            return $this->type ? Dictionary::TYPES[$this->type] : null;
        })->label();
        $grid->column('option', __('Option'));
        $grid->column('slug', __('Slug'));
        $grid->column('alternative', __('Alternative'));
        $grid->column('approved', __('Approved'))->switch();
        $grid->column('sort', __('Sort'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->expand();
            $filter->where(function ($query) {
                $query->where('dictionaries.type', '=', $this->input);
            }, __('Фильтровать по типу'))->select(Dictionary::TYPES);
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
        $show = new Show(Dictionary::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'))->as(function ($status) {
            return Dictionary::TYPES[$status];
        });
        $show->field('option', __('Option'));
        $show->field('slug', __('Slug'));
        $show->field('alternative', __('Alternative'));
        $show->field('approved', __('Approved'));
        $show->field('sort', __('Sort'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Dictionary());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();


        $form->select('type', __('Type'))->options(Dictionary::TYPES);
        $form->text('option', __('Title'))->required();
        $form->text('slug', __('Slug'))->rules('nullable|regex:/(\w\d\_)*/', [
            'regex' => 'Только латинские буквы и знаки подчеркивания',
        ]);
        $form->text('alternative', __('Alternative'));
        $form->switch('approved', __('Approved'))->default(1);
        $form->number('sort', __('Sort'));

        $form->saving(function (Form $form) {
            if ($form->slug === null) {
                $form->slug = Str::slug($form->option);
            }
        });
        return $form;
    }
}
