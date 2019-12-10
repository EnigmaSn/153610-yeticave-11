<?php
require_once('db.php');

/**
 * Функция возвращает массив ассоциативных массивов
 * @param $link - ресурс соединения
 * @param $sql - запрос
 * @return array
 */
//function fetch_all($link, $sql, $params = []) {
//    if ($params) {
//
//    }
//    $sql_result = mysqli_query($link, $sql);
//    $arr = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
//    return $arr;
//}

/**
 * Функция возвращает один ассоциативный массив (конкретная строка из БД)
 * @param $link - ресурс соединения
 * @param $sql - запрос
 * @return array
 */
//function fetch_one($link, $sql) {
//    $sql_result = mysqli_query($link, $sql);
//    $arr = mysqli_fetch_array($sql_result, MYSQLI_ASSOC);
//    return $arr;
//}

/**
 * Получение списка новых лотов
 * @param $link - ресурс соединения (уточнить тип данных)
 * @return array - массив списка лотов
 */
function get_lots($link) {
    $sql = "
    SELECT lots.id  AS lot_id, lots.name AS lot_name, create_date,
    end_date, start_price, img, start_price + step AS current_price, 
    categories.name AS category_name
    FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    ORDER BY lots.create_date DESC;";
    $sql_result = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
    return $lots;
}

/**
 * Получение списка категорий
 * @param $link - ресурс соединения
 * @return array
 */
function get_categories($link) {
    $sql = "
    SELECT id, name, symbol_code FROM categories;";
    $sql_result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
    return $categories;
}

/**
 * Получение информации о конкретном лоте по id из параметра запроса
 * @param $link
 * @param $received_lot_id
 * @return array|null
 */
function get_lot_by_id($link, $received_lot_id) {
    $sql = "SELECT lots.id, create_date, lots.name AS lot_name, description, img, start_price + step AS current_price,
    end_date, step, author_id, winner_id, categories.name AS category_id
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE lots.id = ?;";
    $adv_result = db_get_prepare_stmt($link, $sql, $data = [$received_lot_id]);
    mysqli_stmt_execute($adv_result); // выполняет подготовленный запрос
    $adv_result = mysqli_stmt_get_result($adv_result); // возвращает результат
    $adv = mysqli_fetch_array($adv_result, MYSQLI_ASSOC);
    return $adv;
}
/**
 * Получение информации о ставках конкретного лота по его id
 * @param $link
 * @param $received_lot_id
 * @return array
 */
function get_bets_for_lot($link, $received_lot_id) {
    $sql = "SELECT bets.id, bets.date, user_id, price, lot_id,
        users.name AS user_name, start_price + step AS current_price
        FROM bets
        JOIN users ON bets.user_id = users.id
        JOIN lots ON bets.lot_id = lots.id
        WHERE bets.lot_id = ?;";
    $bets_result = db_get_prepare_stmt($link, $sql, $data = [$received_lot_id]);
    mysqli_stmt_execute($bets_result); // выполняет подготовленный запрос
    $bets_result = mysqli_stmt_get_result($bets_result); // возвращает результат
    $bets = mysqli_fetch_all($bets_result, MYSQLI_ASSOC);
    return $bets;
}

function insert_lot($link, $lot_data) {
    $sql = "INSERT INTO `lots` (create_date, name, description, img, start_price, end_date, step, author_id, category_id)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $lot_data);
    $result = mysqli_stmt_execute($stmt); // выполняет запрос
    return $result; // true если запрос выполнен
}
// TODO экранироватьназвание полей и таблиц ``

function check_email($link, $email) {
    $sql = "SELECT `email`
        FROM `users`
        WHERE `users`.`email` = ?;";
    $email_result = db_get_prepare_stmt($link, $sql, $data = [$email]);
    mysqli_stmt_execute($email_result);
    $email_result = mysqli_stmt_get_result($email_result);
    // $email_is_exist = mysqli_fetch_all($email_result, MYSQLI_ASSOC);
    if (empty($email_result)) {
        return false;
    }
    return true;
}

function insert_user($link, $user_data) {
    $sql = "INSERT INTO users (register_date, email, password, name, contact)
            VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $user_data);
    $result = mysqli_stmt_execute($stmt);
    return $result;
}
