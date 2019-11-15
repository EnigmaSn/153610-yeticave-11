<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');

// в models
// получение списка категорий
$categories_result = mysqli_query($link, '
        SELECT id, name, symbol_code FROM categories;
    ');
// преобразование полученных данных в двумерный массив
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

// проверка на существованиe GET параметра id
// isset()
// empty()

// провека адреса на наличие параметров (4 пункт - вопросы)
// тут проверка существования в базе или просто наличия значения?
// $lot_id = filter_input(INPUT_GET, 'id');

// $_GET['id']; - значение. проверить

// проверка на число
// запрос в базу, возврат значения или ошибки

// написать функции для выборки из БД. models
// fetch_one
// fetch_all

// подготовленные выражения !!
// db_get_prepare_stmt

// TODO Если параметр запроса отсутствует,
// TODO либо если по этому id не нашли ни одной записи, то
// TODO вместо содержимого страницы возвращать код ответа 404.
if (!$_GET) {
    echo '<br>Отсутствует параметр запроса';
    http_response_code(404);
}
$received_lot_id = $_GET['id'];
$received_lot_id = intval($received_lot_id);

if (is_int($received_lot_id)) {
    // Сформируйте и выполните SQL
    // на чтение записи из таблицы с лотами,
    // где id лота равен полученному из параметра запроса.
    // TODO использовать подготовленные запросы
    $lot_result = mysqli_query($link,
        "SELECT lots.id, create_date, lots.name AS lot_name, description, img, start_price + step AS current_price,
        end_date, step, author_id, winner_id, categories.name AS category_id
        FROM lots
        JOIN categories ON lots.category_id = categories.id
        WHERE lots.id = $received_lot_id;
    ");
    // преобразование полученных данных в двумерный массив
    $adv = mysqli_fetch_array($lot_result, MYSQLI_ASSOC);

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

    if (!is_null($lot_result)) {
        $page_content = include_template(
            'lot-main.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
                'bets_count' => $bets_count,
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
        echo '<br>Неверный параметр запроса';
        http_response_code(404);
    }
} else {
    echo '<br>Неверный параметр запроса';
    http_response_code(404);
}
