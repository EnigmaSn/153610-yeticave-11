<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('models/models.php');

// получение списка категорий
$categories = get_categories($link);

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    $error = "Ошибка 404<br>Данной страницы не существует на сайте.";
    $page_content = include_template('error.php', [
        'categories' => $categories,
        'error' => $error
    ]);
} else {
    $received_lot_id = $_GET['id'];

    $adv = get_lot_by_id($link, $received_lot_id);
    $bets = get_bets_for_lot($link, $received_lot_id);

    // результат, если такой лот есть в БД
    if (!is_null($adv)) {
        $page_content = include_template(
            'lot.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
            ]
        );
    } else {
        http_response_code(404);
        $error = "Ошибка 404<br>Данной страницы не существует на сайте.";
        $page_content = include_template('error.php', [
            'categories' => $categories,
            'error' => $error
        ]);
    }
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        //'user_name' => $user_name,
        'title' => $adv['lot_name'] ?? 'Ошибка',
        'categories' => $categories,
        'flatpickr' => false
    ]
);
print($layout_content);
