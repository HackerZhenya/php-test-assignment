<p align="center">Based on<br><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## php-test-assignment

Чтобы запустить проект, нужно:

1. Установить зависимости

```shell
composer update
```

2. Выполнить миграции 

```shell
php artisan migrate
```

Можно сразу создать тестовые данные, если добавить флаг `--seed`

```shell
php artisan migrate --seed
```

3. Запусить сервер

```shell
php artisan serve
```

4. Готово! Запросы для проверки работоспособности:

```
http://localhost:8080/users?age[from]=18&age[to]=24&gender=male&hobby[]=football&hobby[]=snowboarding
```

```
http://localhost:8080/users?geo_location[nw][lat]=52.57&geo_location[nw][lng]=0&geo_location[se][lat]=0&geo_location[se][lng]=56.06
```