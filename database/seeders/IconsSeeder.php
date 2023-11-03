<?php

namespace Database\Seeders;

use App\Models\Icons;
use Illuminate\Database\Seeder;

class IconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $icons = [
            ['unicode' => 'f03e', 'code' => 'fa-picture-o', 'name' => 'Фото'],
            ['unicode' => 'f1da', 'code' => 'fa-history', 'name' => 'История'],
            ['unicode' => 'f1b9', 'code' => 'fa-car', 'name' => 'Автомобиль'],
            ['unicode' => 'f085', 'code' => 'fa-cogs', 'name' => 'Настройки'],
            ['unicode' => 'f06e', 'code' => 'fa-eye', 'name' => 'Глаз'],
            ['unicode' => 'f080', 'code' => 'fa-bar-chart', 'name' => 'График'],
            ['unicode' => 'f240', 'code' => 'fa-battery', 'name' => 'Заряд батареи'],
            ['unicode' => 'f040', 'code' => 'fa-pencil', 'name' => 'Карандаш'],
            ['unicode' => 'f09e', 'code' => 'fa-rss', 'name' => 'Новости'],
            ['unicode' => 'f055', 'code' => 'fa-plus-circle', 'name' => 'Плюс'],
            ['unicode' => 'f240', 'code' => 'fa-battery', 'name' => 'Заряд батареи'],
            ['unicode' => 'f013', 'code' => 'fa-cog', 'name' => 'Настройка'],
            ['unicode' => 'f238', 'code' => 'fa-train', 'name' => 'Поезд'],
            ['unicode' => 'f005', 'code' => 'fa-star', 'name' => 'Звезда'],
            ['unicode' => 'f030', 'code' => 'fa-camera', 'name' => 'Фотоаппарат'],
            ['unicode' => 'f02e', 'code' => 'fa-bookmark', 'name' => 'Закладка'],
            ['unicode' => 'f132', 'code' => 'fa-shield', 'name' => 'Щит'],
            ['unicode' => 'f0e0', 'code' => 'fa-envelope', 'name' => 'Почта'],
            ['unicode' => 'f080', 'code' => 'fa-baк-chart', 'name' => 'График'],
            ['unicode' => 'f072', 'code' => 'fa-plane', 'name' => 'Самолет'],
            ['unicode' => 'f008', 'code' => 'fa-video-film', 'name' => 'Видео'],
            ['unicode' => 'f0ac', 'code' => 'fa-globe', 'name' => 'Глобус'],
            ['unicode' => 'f1de', 'code' => 'fa-sliders', 'name' => 'Слайдер'],
            ['unicode' => 'f21e', 'code' => 'fa-heartbeat', 'name' => 'Сердце'],
            ['unicode' => 'f16a', 'code' => 'fa-youtube-play', 'name' => 'Youtube'],
            ['unicode' => 'f135', 'code' => 'fa-rocket', 'name' => 'Ракета'],
            ['unicode' => 'f05a', 'code' => 'fa-info-circle', 'name' => 'Информация'],
            ['unicode' => 'f02d', 'code' => 'fa-book', 'name' => 'Книга'],
            ['unicode' => 'f039', 'code' => 'fa-align-justify', 'name' => 'Бургер'],
            ['unicode' => 'f039', 'code' => 'fa-align-justify', 'name' => 'Бургер'],
            ['unicode' => 'f05e', 'code' => 'fa-ban', 'name' => 'Бан'],
            ['unicode' => 'f0f4', 'code' => 'fa-coffee', 'name' => 'Кофе'],
            ['unicode' => 'f2bd', 'code' => 'fa-user-circle', 'name' => 'Пользователь'],
            ['unicode' => 'f02d', 'code' => 'fa-book', 'name' => 'Книга'],
            ['unicode' => 'f073', 'code' => 'fa-calendar', 'name' => 'Календарь'],
            ['unicode' => 'f09d', 'code' => 'fa-credit-card', 'name' => 'Кредитная карта'],
            ['unicode' => 'f19c', 'code' => 'fa-bank', 'name' => 'Банк'],
            ['unicode' => 'f07b', 'code' => 'fa-folder', 'name' => 'Папка'],
            ['unicode' => 'f1f8', 'code' => 'fa-trash', 'name' => 'Корзина'],
            ['unicode' => 'f15a', 'code' => 'fa-btc', 'name' => 'Биткоин'],
            ['unicode' => 'f0b0', 'code' => 'fa-filter', 'name' => 'Фильтр'],
            ['unicode' => 'f0f5', 'code' => 'fa-cutlery', 'name' => 'Столовые приборы'],
            ['unicode' => 'f03d', 'code' => 'fa-video-camera', 'name' => 'Камера'],
            ['unicode' => 'f12e', 'code' => 'fa-puzzle-piece', 'name' => 'Пазл'],
            ['unicode' => 'f0f3', 'code' => 'fa-bell', 'name' => 'Колокольчик'],
            ['unicode' => 'f1fb', 'code' => 'fa-eyedropper', 'name' => 'Пипетка'],
        ];
        foreach ($icons as $icon) {
            Icons::create([
                'unicode' => $icon['unicode'],
                'name' => $icon['name'],
                'code' => $icon['code']
            ]);
        }
    }
}
