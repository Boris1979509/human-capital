## Человеческий капитал

## Сборка проекта

```
composer update
```
```
php artisan migrate
```
Установка административной панели
```
php artisan admin:install
```
```
php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
```
WYSIWYG
```
php artisan vendor:publish --tag=laravel-admin-ckeditor
```
Заполнение базы данных
```
php artisan db:seed
```
Laravel passport
```
php artisan passport:install
```
[Перейти в административную панель](https://chelkap.loc/admin/auth/login)
