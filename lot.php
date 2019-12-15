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
    $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
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
        //echo 'Нет лота в БД';
        http_response_code(404);
        $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Страница лота',
        'is_auth' => $is_auth,
        'categories' => $categories,
        'flatpickr' => false
    ]
);
print($layout_content);
