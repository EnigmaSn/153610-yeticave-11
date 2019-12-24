<?php
require_once('helpers.php');
require_once('init.php');
require_once('functions.php');
require_once('models/models.php');

// получение списка категорий
$categories = get_categories($link);
$errors = [];

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    $error = "Ошибка 404. Данной страницы не существует на сайте.";
    $page_content = include_template('error.php', [
        'categories' => $categories,
        'error' => $error
    ]);
} else {
    $received_lot_id = $_GET['id'];

    $adv = get_lot_by_id($link, $received_lot_id);
    $bets = get_bets_for_lot($link, $received_lot_id);

    // результат, если такой лот отсутствует в БД
    if (empty($adv)) {
        http_response_code(404);
        $error = "Ошибка 404. Данной страницы не существует на сайте.";
        $page_content = include_template('error.php', [
            'categories' => $categories,
            'error' => $error
        ]);
    } else {
        $adv['min_next_bet'] = ($adv['max_bet'] ?? $adv['current_price'])
            + $adv['step'];
        $current_price = $adv['max_bet'];

        // добавление ставки только если лот существует
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $bet_from_form = (int)get_add_bet_form_data($_POST)['cost'];
            $errors = validate_bet_form($bet_from_form, $adv['min_next_bet'], $adv['author_id']);

            add_bet(
                $bet_from_form,
                $errors,
                $categories,
                $adv,
                $bets,
                $link,
                $received_lot_id,
                $_SESSION['user']['id']
            );
        }

        $page_content = include_template(
            'lot.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
                'errors' => $errors ?? null
            ]
        );
    }
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'title' => $adv['lot_name'] ?? 'Ошибка',
        'categories' => $categories,
        'flatpickr' => false
    ]
);
print($layout_content);
