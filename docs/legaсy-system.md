# Premium-Avto (Legacy version)

# Premium-Avto (Legacy System)

> Главный технический документ проекта.
> Перед началом любого нового этапа разработки сначала ознакомиться с этим файлом.
> Все подтверждённые технические открытия заносятся сюда.

## 1. Источник проекта
- сайт premium-avto.ru
- дата резервной копии
- версия PHP
- база данных

## 2. Структура проекта

_ajax/
_core/
admin/
public_html/
signin/

назначение каждой папки

## 3. Как работает сайт

HTTP
    ↓
public_html/index.php
    ↓
_core/_parser/page.php
    ↓
PAGE::init()
    ↓
PAGE::html()
    ↓
template landing.html

## 4. Маршрутизация

index.php
page.php
path.php

## 5. Шаблоны

landing.html
cabinet.html
print.html

## 6. База данных

detalauto_pa

таблицы

seo
texts
gallery
users

назначение каждой

## 7. Конфигурация

config.php

какие константы

DB_SERVER
DB_NAME
COOKIE...

(без настоящих паролей)

## 8. Админка

что умеет

## 9. Что уже выяснили

- движок самописный
- MVC нет
- шаблоны html
- один вход index.php
- маршрутизация через page.php

## 10. Что ещё неизвестно

...