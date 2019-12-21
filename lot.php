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
        //var_dump($adv);
        $adv['min_next_bet'] = ($adv['max_bet'] ?? $adv['current_price'])
            + $adv['step'];
        var_dump($adv['min_next_bet']);
        // разобраться
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $bet_from_form = (int) get_add_bet_form_data($_POST)['cost'];
            var_dump($bet_from_form);
            $errors = validate_bet_form($bet_from_form, $adv['min_next_bet'], $adv['author_id']);

            if (count($errors)) {
                $page_content = include_template(
                    'lot.php',
                    [
                        'categories' => $categories,
                        'adv' => $adv,
                        'bets' => $bets,
                        'error_bet' => $error_bet ?? null
                    ]
                );
            } else {
                echo "Ошибок нет";
//                var_dump((int) $_SESSION['user']['id']);
//                var_dump($bet_from_form);
//                var_dump($received_lot_id);
                $bet_added = insert_bet($link, (int) $bet_from_form, (int) $received_lot_id, (int) $_SESSION['user']['id']);
//                var_dump($bet_added);

                if ($bet_added) {
                    echo "Ставка добавлена";
                    $lot = get_lot_by_id($link, $received_lot_id);
                    $bets = get_bets_for_lot($link, $received_lot_id);

                    //header("Location: lot.php?id=" . $_GET['id']);
                } else {
                    echo "Ставка НЕ добавлена";
                }

            }
        }

        $page_content = include_template(
            'lot.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
                'error_bet' => $error_bet ?? null
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
// проверка ставки
//$errors = [];
//
//if (!empty($_POST)) {
//    $bet_data = get_add_bet_data($_POST);
//    $errors = validate_bet_form($link, $bet_data);
//    var_dump($bet_data);
//}


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
