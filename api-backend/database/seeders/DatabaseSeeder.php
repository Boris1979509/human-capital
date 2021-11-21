<?php

namespace Database\Seeders;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Menu::insert([

            [
                'parent_id' => 0,
                'order' => 0,
                'title' => 'Контент',
                'icon' => 'fa-file-text-o',
                'uri' => 'contents',
            ],
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => 'Подборки',
                'icon' => 'fa-copy',
                'uri' => 'selections',
            ],
            [
                'parent_id' => 0,
                'order' => 9,
                'title' => 'UI панели',
                'icon' => 'fa-columns',
                'uri' => 'panels',
            ],
            [
                'parent_id' => 0,
                'order' => 9,
                'title' => 'Автоподсказки',
                'icon' => 'fa-bars',
                'uri' => 'autocomplete-words',
            ],
            [
                'parent_id' => 0,
                'order' => 10,
                'title' => 'Профессии',
                'icon' => 'fa-group',
                'uri' => 'professions',
            ],
            [
                'parent_id' => 0,
                'order' => 11,
                'title' => 'Города',
                'icon' => 'fa-map-marker',
                'uri' => 'cities',
            ],
            [
                'parent_id' => 0,
                'order' => 12,
                'title' => 'Университеты',
                'icon' => 'fa-bank',
                'uri' => 'universities',
            ],
            [
                'parent_id' => 0,
                'order' => 13,
                'title' => 'Справочники',
                'icon' => 'fa-database',
                'uri' => 'dictionaries',
            ],
            [
                'parent_id' => 0,
                'order' => 13,
                'title' => 'Теги',
                'icon' => 'fa-bars',
                'uri' => 'tags',
            ],
            [
                'parent_id' => 0,
                'order' => 14,
                'title' => 'Пользователи',
                'icon' => 'fa-user',
                'uri' => 'users',
            ],
        ]);
    }
}
