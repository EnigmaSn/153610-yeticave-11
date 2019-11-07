USE yeticave;

-- регулярки из php в sql

-- Добавление категорий
INSERT INTO categories SET name = 'Доски и лыжи', symbol_code = 'boards';
INSERT INTO categories SET name = 'Крепления', symbol_code = 'attachment';
INSERT INTO categories SET name = 'Ботинки', symbol_code = 'boots';
INSERT INTO categories SET name = 'Одежда', symbol_code = 'clothing';
INSERT INTO categories SET name = 'Инструменты', symbol_code = 'tools';
INSERT INTO categories SET name = 'Разное', symbol_code = 'other';

-- добавление юзеров
INSERT INTO users SET
    register_date = NOW(),
    email = 'the_boy_who_lived@hogwarts.uk',
    name = 'Harry Potter',
    password = 'password',
    contact = 'facebook.com/harry_potter';
INSERT INTO users SET
    register_date =  NOW(),
    email = 'the_half-blood_prince@hogwarts.uk',
    name = 'Severus Snape',
    password = 'password2',
    contact = 'facebook.com/severus_snape';

-- добавление объявлений
INSERT INTO lots SET
        create_date = NOW(),
        name = '2014 Rossignol District Snowboard',
        description = 'Это ноуборд. Хороший такой. Берите',
        img = 'lot-1.jpg',
        start_price = 10999,
        end_date = '2019-10-04',
        step = 500,
        author_id = 1,
        winner_id = null,
        category_id = 1;
INSERT INTO lots SET
        create_date = NOW(),
        name = 'DC Ply Mens 2016/2017 Snowboard',
        description = 'Почти новый сноуборд',
        img = 'lot-2.jpg',
        start_price = 159999,
        end_date = '2019-12-19',
        step = 100,
        author_id = 2,
        winner_id = null,
        category_id = 1;
INSERT INTO lots SET
        create_date = NOW(),
        name = 'Крепления Union Contact Pro 2015 года размер L/XL',
        description = 'Крепления. Целые',
        img = 'lot-3.jpg',
        start_price = 8000,
        end_date = '2020-01-20',
        step = 200,
        author_id = 1,
        winner_id = null,
        category_id = 2;
INSERT INTO lots SET
        create_date = NOW(),
        name = 'Ботинки для сноуборда DC Mutiny Charocal',
        description = 'Ботинки для сноуборда. 43 размер',
        img = 'lot-4.jpg',
        start_price = 10999,
        end_date = '2020-03-03',
        step = 700,
        author_id = 2,
        winner_id = null,
        category_id = 3;
INSERT INTO lots SET
    create_date = NOW(),
    name = 'Куртка для сноуборда DC Mutiny Charocal',
    description = 'Куртка для сноуборда. Теплая',
    img = 'lot-5.jpg',
    start_price = 7500,
    end_date = '2019-12-31',
    step = 300,
    author_id = 1,
    winner_id = null,
    category_id = 4;
INSERT INTO lots SET
    create_date = NOW(),
    name = 'Маска Oakley Canopy',
    description = 'Крутая маска',
    img = 'lot-6.jpg',
    start_price = 5400,
    end_date = '2019-12-25',
    step = 1000,
    author_id = 1,
    winner_id = null,
    category_id = 6;

-- добавление ставок для объявлений
INSERT INTO bets SET
    date = NOW(),
    price = 20000,
    user_id = 2,
    lot_id = 1;
INSERT INTO bets SET
    date = NOW(),
    price = 16000,
    user_id = 1,
    lot_id = 2;

-- запрос на чтение всех категорий
SELECT * FROM categories;

-- получить самые новые, открытые лоты.
-- Каждый лот должен включать название,
-- стартовую цену, ссылку на изображение,
-- текущую цену (через сложение и AS?),
-- название категории;
SELECT lots.name, start_price, img, start_price + step AS current_price, categories.name
FROM lots
-- добавляем таблицу категорий
-- для показа имени всемто id категории
JOIN categories ON lots.category_id = categories.id
ORDER BY lots.create_date DESC;

-- показать лот по его id
-- Получите также название категории, к которой принадлежит лот;
SELECT *, categories.name FROM lots
JOIN categories ON lots.category_id = categories.id
WHERE lots.id = 1;

-- обновить название лота по его идентификатору;
UPDATE lots SET name = 'Измененное название лота'
WHERE lots.id = 1;

-- получить список ставок для лота
-- по его идентификатору с сортировкой по дате.
SELECT * FROM bets
JOIN lots ON bets.lot_id = lots.id
WHERE lots.id = 1
ORDER BY bets.date DESC;

