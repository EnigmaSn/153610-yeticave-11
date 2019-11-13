<?php
    require_once('helpers.php');
    require_once('init.php');
    require_once('data.php');
    require_once('functions.php');
    require_once('db.php');



    // получение списка новых лотов
    $lots_result = mysqli_query($link, '
        SELECT lots.id  AS lot_id, lots.name AS lot_name, create_date, end_date, start_price, img, start_price + step AS current_price, categories.name AS category_name
        FROM lots JOIN categories ON lots.category_id = categories.id 
        ORDER BY lots.create_date DESC;');
    // преобразование полученных данных в двумерный массив
    $ads = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);

    // получение списка категорий
    $categories_result = mysqli_query($link, '
        SELECT id, name, symbol_code FROM categories;
    ');
    // преобразование полученных данных в двумерный массив
    $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
    // ВОПРОС: в качестве url мы подставляет символьный код категории?

    // добавление новой категории
    mysqli_query($link, 'INSERT INTO categories SET name = \'test-in-php\', symbol_code = \'test-in-php\';');
    mysqli_query($link, 'INSERT INTO categories SET name = \'test-in-php\', symbol_code = \'test-in-php\';');
    // сначала вставляла запрос в файл queries, но там было ноль реакции,
    // но после запроса из PHP появились оба варианта
    // с чем это связано?

    // а категория вообще добавляется при каждом обновлении страницы
    // как это работает?
    mysqli_query($link, '
        INSERT INTO lots SET
            `create_date` = NOW(),
            `name` = "Тестовый лот из PHP",
            `description` = \'Это ноуборд. Хороший такой. Берите\',
            img = \'lot-1.jpg\',
            start_price = 10999,
            end_date = \'2019-10-04\',
            step = 500,
            author_id = 1,
            winner_id = null,
            category_id = 1;
    ');

    $page_content = include_template(
        'main.php',
        [
            'categories' => $categories,
            'ads' => $ads,
        ]
    );
    $layout_content = include_template(
        'layout.php',
        [
            'page_content' => $page_content,
            'user_name' => $user_name,
            'title' => 'Yeti Cave - Главная страница',
            'is_auth' => $is_auth,
            'categories' => $categories,
        ]
    );
    print($layout_content);
