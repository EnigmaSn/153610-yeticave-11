<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');
require_once('models/models.php');

// получение списка категорий
$categories = get_categories($link, $categories_sql);

// TODO написать функции для выборки из БД. models (fetch_one, fetch_all)

if ( !isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])){
    // echo '<br>Параметр запроса неверный либо отсутствует <br>';
    http_response_code(404);
    $page_content = include_template('error-main.php', ['error' => mysqli_error($link)]);
} else {
    $received_lot_id = $_GET['id'];

    // SQL-запрос на чтение лота с указанным в get-параметре id
    // TODO использовать подготовленные запросы (db_get_prepare_stmt)
    $adv = get_lot_by_id($link, $received_lot_id);

    $bets_result = mysqli_query($link,
        "SELECT bets.id, bets.date, user_id, price, lot_id,
        users.name AS user_name, start_price + step AS current_price
    
        FROM bets
        JOIN users ON bets.user_id = users.id
        JOIN lots ON bets.lot_id = lots.id
        WHERE bets.lot_id = $received_lot_id;
    ");
    $bets_count = mysqli_num_rows($bets_result);
    $bets = mysqli_fetch_all($bets_result, MYSQLI_ASSOC);

    // результат, если такой лот есть в БД
    if (!is_null($adv)) {
        $page_content = include_template(
            'lot-main.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
                'bets_count' => $bets_count,
            ]
        );
    } else {
        //echo 'Нет лота в БД';
        http_response_code(404);
        $page_content = include_template('error-main.php', ['error' => mysqli_error($link)]);
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
    ]
);
print($layout_content);
