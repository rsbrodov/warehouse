#INSTALL Project

При первом запуске на сервере выполнить:
<p>php artisan db:seed --class=RoleSeeder</p>
<p>php artisan make:seeder AdminUserSeeder</p>
<p>git clone https://github.com/rsbrodov/cms.git</p>
<p>composer update</p>
<p>npm install</p>
<p>npm run dev</p>
<p>Создать базу с названием cms тип utf8mb4_unicode_ci</p>
<p>Изменить .env</p>
<p>php artisan migrate</p>
<p>php artisan key:generate</p>
<p>php artisan serve</p>
<p>Скопировать ссылку из консоли в браузер</p>
Если не загружаются картинки нужно настроить storage в корне проекта: php artisan storage:link 
