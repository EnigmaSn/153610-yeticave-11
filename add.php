<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');
require_once('models/models.php');

// получение списка категорий
$categories = get_categories($link);

// проверка на отправку методом POST (сценарий вызван отправкой формы)
if (!empty($_POST)) {
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
    $rules = [
        'lot-name' => null,
        'category' => null, // TODO добавить проверку на существование id категории в базе
        'message' => null,
        'lot-image' => function() {
//            if () {
//
//            }
        },
        'lot-rate' => function() {
            if ($_POST['lot-rate'] <= 0) {
                return "Начальная цена должна быть больше ноля";
            }
        },
        'lot-step' => function() {
            if ($_POST['lot-step'] <= 0)  {
                return "Шаг ставки должен быть больше ноля";
            }
        },
        'lot-date' => function() {
            $format = is_date_valid($_POST['lot-date']); // boolean
            $current_date = date_create("now");
            $current_date = date_format($current_date, 'Y-m-d');
            //var_dump($_POST['lot-date'] < $current_date);
            if (!$format || $_POST['lot-date'] < $current_date) {
                return "Неверная дата";
            }
        }
    ];

    // возврат значений для всех указанных полей
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

    foreach ($fields as $key => $value) {
        if (isset($rules[$key])) { // если в массиве правил есть правило для соответствующего поля
            $rule = $rules[$key]; // извлекат правило-функцию
            $errors[$key] = $rule($value); // вызывает ее, передевая значение
        }
        // если текущее поле в списке обязательны и оно пустое
        if (in_array($key, $required_fields) && empty($value)) {
            $errors[$key] = "Поле $key неоходимо заполнить";
        }
    }
    // фильтрует по callback, но если его нет, то удаляет пустые значения
    $errors = array_filter($errors);
    var_dump($errors);

    // TODO проверка файла
    // TODO вывести классы ошибок в шаблон
    // TODO добавить валидацию select (optional?)

    if (count($errors)) {
        $page_content = include_template(
            'add.php',
            [
                'fields' => $fields,
                'errors' => $errors,
                'categories' => $categories,
            ]
        );
    }
    // проверка на пустоту
//    foreach ($required_fields as $field) {
////        echo validate_filled($field);
//        if (empty($field)) {
//            $errors[$field] = "Поле должно быть заполнено";
//        }
//        if ($field === 'lot-step') {
//            //if (validate_lot_step)
//            // сделать через $rules
//        }
//    }

    // Проверка начальной цены
    //echo validate_lot_rate($required_fields[4]);

    // Проверка даты завершения лота (доделать функцию)

    // Проверка шага ставки
    //echo validate_lot_step($required_fields[5]);
} else {
    $page_content = include_template(
        'add.php',
        [
            'categories' => $categories,
        ]
    );
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Страница добавления лота',
        'is_auth' => $is_auth,
        'categories' => $categories,
        'flatpickr' => true
    ]
);
print($layout_content);
