# Blog API

## Установка и запуск проекта

1. Клонируйте репозиторий:

```bash
git clone https://github.com/Lepessov/blog-api.git

cd blog-api
```
### Устанавливаем зависимости
```
composer install
```
### Клонируем шаблон .env файла

```
cp .env.example .env
```
### Поднимаем контейнеры
```
./vendor/bin/sail up

./vendor/bin/sail artisan migrate
```

Создаем юзера
```
./vendor/bin/sail artisan db:seed
```

### Сваггер

```
./vendor/bin/sail artisan l5-swagger:generate
```

### Остановить проект
```
./vendor/bin/sail down
```

 ### Проект готов!

 по localhost/api/documentation можем делать запросы под
 
```
 test@example.com
 password
```
 
