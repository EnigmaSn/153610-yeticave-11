<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');

// получение списка категорий
$categories_result = mysqli_query($link, '
        SELECT id, name, symbol_code FROM categories;
    ');
// преобразование полученных данных в двумерный массив
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

// провека адреса на наличие параметров (4 пункт - вопросы)
// тут проверка существования в базе или просто наличия значения?
$lot_id = filter_input(INPUT_GET, 'id');
if ($lot_id) {
    $page_content = include_template(
        'lot-main.php',
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
            'title' => 'Страница лота',
            'is_auth' => $is_auth,
            'categories' => $categories,
        ]
    );
    print($layout_content);
} else {
    echo '<br> Нет параметра запроса';
}

