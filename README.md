## Пример реализации тестового задания, по вакансии от Avito

Исходное задание [https://github.com/avito-tech/safedeal-backend-trainee](https://github.com/avito-tech/safedeal-backend-trainee)

### Что сделано:
- Реализован рабочий REST API
- Использовали Slim v4
- Был реализован RateLimit с использованием Redis
- Для работы с БД был использован illuminate/database
- Пример БД находится в файле slim.sql

### Документация:
* Получение цены доставки
    * GET ```/delivery/calculate```
        * Параметры:
            * delivery_address
            * cart_id
            
* Создание задания курьеру, на доставку
    * POST ```/delivery/create```
        * Параметры:
            * order_id
            * delivery_address
            * delivery_date
            * memo
            * manager_key
            
* Получение информации о доставки, для курьера
    * GET ```/delivery/courier/find```
        * Параметры:
            * courier_key
            * delivery_code
            
* Создание заказа
    * POST ```/orders/create```
        * Параметры:
            * delivery_address
            * cart_id
            
* Получение списка всех заказов
    * GET ```/orders/get/all```
        * Параметры:
            * manager_key
            
* Получение подробной информации о заказе
    * GET ```/orders/get/{id}```
        * Параметры:
            * manager_key
            
            