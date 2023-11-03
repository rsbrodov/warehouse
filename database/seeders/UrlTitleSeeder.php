<?php

namespace Database\Seeders;

use App\Models\UrlTitle;
use Illuminate\Database\Seeder;

class UrlTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urlTitles = [
            ['url' => 'dictionary.index', 'title' => 'Контентная модель / Справочники'],
            ['url' => 'type-content.index', 'title' => 'Контентная модель / Типы контента'],
            ['url' => 'type-content.view-new', 'title' => 'Контентная модель / Типы контента'],
            ['url' => 'users.profile', 'title' => 'Личный кабинет пользователя'],
            ['url' => 'users.index', 'title' => 'Пользователи'],
            ['url' => 'element-content.index', 'title' => 'Менеджер контента'],
            ['url' => 'type-content.enter', 'title' => 'Менеджер контента'],
            ['url' => 'type-content.all-version', 'title' => 'Типы контента / История изменений'],
            ['url' => 'type-content.enter-vue', 'title' => 'Менеджер контента'],
            ['url' => 'element-content.all-version', 'title' => 'Менеджер контента / История изменений'],
            ['url' => 'dictionary-element.index', 'title' => 'Элементы справочника'],
            ['url' => 'users.create', 'title' => 'Создание пользователя'],
            ['url' => 'users.show', 'title' => 'Информация о пользователе'],
            ['url' => 'element-content.indexAll', 'title' => 'Менеджер контента / Весь контент'],
        ];

        foreach ($urlTitles as $urlTitle) {
            UrlTitle::create([
                'url'    => $urlTitle['url'],
                'title'  => $urlTitle['title'],
            ]);
        }
    }
}
