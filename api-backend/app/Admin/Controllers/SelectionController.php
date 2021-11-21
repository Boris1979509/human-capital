<?php

namespace App\Admin\Controllers;

use App\Models\Selection\Selection;
use App\Models\Selection\SelectionItem;
use App\Models\Selection\SelectionModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SelectionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Подборки';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Selection());
        $grid->model()->orderBy('id', 'desc');
        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->column('id', __('Id'));
        $grid->column('author_id', __('Author id'));
        $grid->column('title', __('Title'));
        $grid->column('annotation', __('Annotation'));
        $grid->column('description', __('Description'));
        $grid->column('published_at', __('Published at'));
        $grid->column('is_published', __('Is published'))->switch();
        $grid->column('is_advertisement', __('is_advertisement'));
        $grid->column('image', __('Cover'))->display(function () {
            $cover = $this->getFirstMedia('cover') ? $this->getFirstMedia('cover')->getUrl('thumb') : null;
            return [$cover];
        })->carousel();

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
        $show = new Show(Selection::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('author_id', __('Author id'));
        $show->field('title', __('Title'));
        $show->field('annotation', __('Annotation'));
        $show->field('description', __('Description'));
        $show->field('published_at', __('Published at'));
        $show->field('is_published', __('Is published'));
        $show->field('is_advertisement', __('is_advertisement'));
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
        $form = new Form(new Selection());
        $form->tools(function (Form\Tools $tools) {
            // Disable `View` btn.
            $tools->disableView();
        });


        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

//        $form->number('author_id', __('Author id'));
        $form->text('title', __('Title'));
        $form->text('slug', __('Slug'));
        $form->text('annotation', __('Annotation'));
        $form->textarea('description', __('Description'));
        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));
        $form->switch('is_published', __('Is published'))->default(true);
        $form->switch('is_advertisement', __('is_advertisement'))->default(false);
        $form->html(function () {
            if ($this->getFirstMedia('cover')) {
                return '<img src="' . $this->getFirstMedia('cover')->getUrl('thumb') . '" width="50px"/>';
            }
            return null;
        });
        $form->image('image', __('Image'))
            ->options(['showClose' => false])
            ->options(['fileActionSettings' => ['showRemove' => true]]);

        $form->hasMany('items', __('admin.items'), function (Form\NestedForm $form) {
            $form->hidden('id');
            $form->select('selectionable_type', __('Type'))->options(SelectionModel::OPTIONS);
            $form->select('selectionable_id', __('Content ID'));
            $form->text('description', __('Description'));
            $form->image('selectionable_image', __('field.image'));
        });

        $form->submitted(function (Form $form) {
            $form->ignore('image');
        });

        $form->saving(function (Form $form) {
            if ($form->slug === null) {
                $form->slug = Str::slug($form->title);
            }

            // Загружаем картинку
            if (request()->file('image')) {
                $form->model()
                    ->addMediaFromRequest('image')
                    ->usingFileName(request()->file('image')->hashName())
                    ->toMediaCollection('cover');
            }
        });

        // Загружаем картинки для items
        $form->saved(function (Form $form) {
            if ($form->items) {
                foreach ($form->items as $itemId => $item) {
                    if (isset($item['id'])) {
                        $sItem = SelectionItem::find($item['id']);
                    } else {
                        $sItem = SelectionItem::where('selectionable_id', '=', $item['selectionable_id'])
                            ->where('selectionable_type', '=', $item['selectionable_type'])
                            ->first();
                    }
                    if (request()->file('items.' . $itemId . '.selectionable_image')) {
                        $sItem->addMediaFromRequest('items.' . $itemId . '.selectionable_image')
                            ->usingFileName(request()->file('items.' . $itemId . '.selectionable_image')->hashName())
                            ->toMediaCollection('cover');
                    }
                }
            }
        });
        return $form;
    }
}
