<?php

namespace App\Admin\Controllers;

use App\Models\UI\AutocompleteWord;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class AutocompleteWordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AutocompleteWord';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AutocompleteWord());
        $grid->disableRowSelector();
        $grid->disableExport();

        $grid->tools(function ($tools) {
            $tools->append('<a href="#" id="update-autocomplete-words" class="btn btn-label btn-warning">Обновить из данных пользователей</a>');
        });

        $grid->column('id', __('Id'));
        $grid->column('type', __('Type'))->display(function () {
            return $this->type ? AutocompleteWord::OPTIONS[$this->type] : null;
        })->label();
        $grid->column('word', __('Word'));
        $grid->column('approved', __('Approved'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->expand();
            $filter->where(function ($query) {
                $query->where('autocomplete_words.type', '=', $this->input);
            }, __('Фильтровать по типу'))->select(AutocompleteWord::OPTIONS);
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
        $show = new Show(AutocompleteWord::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('word', __('Word'));
        $show->field('approved', __('Approved'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AutocompleteWord());
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->select('type', __('Type'))->options(AutocompleteWord::OPTIONS);
        $form->text('word', __('Word'));
        $form->switch('approved', __('Approved'))->default(1);

        $form->saving(function (Form $form) {
            $form->model()->timestamps = false;
            $form->word = Str::lower($form->word);
        });
        return $form;
    }
}
