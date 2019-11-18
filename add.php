<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');
require_once('models/models.php');

// получение списка категорий
$categories = get_categories($link, $categories_sql);

// проверка на отправку методом POST (сценарий вызван отправкой формы)
if (isset($_POST)) {
    var_dump($_POST);
    // массив обязательных полей
    $required_fields = [
        'lot-name',
        'category',
        'message',
        'lot-image',
        'lot-rate',
        'lot-step',
        'lot-date'
    ];
    // массив неправильно заполненных полей
    $errors = [];
    // поля и функции для их проверки
//    $rules = [
//        'lot-name' => ,
//        'category' => ,
//        'message' => ,
//        'lot-image' => ,
//        'lot-rate' => ,
//        'lot-step' => ,
//        'lot-date' =>
//    ];

    $fields = filter_input_array(INPUT_POST,
    [
        'lot-name' => FILTER_DEFAULT, // без фильтра
        'category' => FILTER_DEFAULT,
        'message' => FILTER_DEFAULT,
        'lot-image' => FILTER_DEFAULT,
        'lot-rate' => FILTER_DEFAULT,
        'lot-step' => FILTER_DEFAULT,
        'lot-date' => FILTER_DEFAULT
    ],
    true // добавляет в результат отсутствующие ключи со значением null
    );

    // проверка на пустоту
    foreach ($required_fields as $field) {
        echo validate_filled($field);
    }

    // Проверка начальной цены
    echo validate_lot_rate($required_fields[4]);

    // Проверка даты завершения лота (доделать функцию)

    // Проверка шара ставки
    echo validate_lot_step($required_fields[5]);

    if (count($errors)) {
        echo 'Ошибка валидации';
    }
}

$page_content = include_template(
    'add-main.php',
    [
        'categories' => $categories,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Страница добавления лота',
        'is_auth' => $is_auth,
        'categories' => $categories,
    ]
);
print($layout_content);
