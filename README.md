**Result**

Класс описывает результат. Используется для формирования JSON ответов, а также для сбора результатов операций

**Пример использования**

```php
$result = new \Desin\Result();
$result->setError("При операции произошла ошибка");
```

Также при необходимости можно приложить к ответу какие-либо данные

```php
$result->setData([
    "some" => "Some Data",
]);
```

Выводим результат

```php
echo $result->getJSON();
exit();
```

Данный пример выведет в формате JSON результат такого вида: 

```json
{
    "success": false,
    "message": "При операции произошла ошибка",
    "data": {
        "some": "Some Data"
    }
}
```