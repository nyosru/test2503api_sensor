ЛАравель апи для сенсоров

как развернуть:
1) клонируете репозиторий и запуск как лара
2) копируете .env.example в .env
3) запускаете свагер /api/documentation и кликаете апи

настройка бд не нужна так как используется sqlite (для теста пойдёт)

добавил свагер чтобы покликать как работает апи
он доступен по адресу
/api/documentation

созданы 2 маршрута для апи

/api - сюда шлём данные
/api/history - запрос на получение данных, добавил рамки даты времени чтобы можно было делать ограниченный запрос
