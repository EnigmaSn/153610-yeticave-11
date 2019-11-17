<?php

function fetch_all ($link, $sql) {
    $sql_result = mysqli_query($link, $sql);
    $arr = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
    return $arr;
}

function fetch_one ($object) {
    $arr = mysqli_fetch_array($object, MYSQLI_ASSOC);
    return $arr;
}

/**
 * Получение списка новых лотов
 * @param $link - подлючение (уточнить тип данных)
 * @return array - массив списка лотов
 */
function get_lots ($link, $lots_sql) {
//    $lots_result = mysqli_query($link, '
//        SELECT lots.id  AS lot_id, lots.name AS lot_name, create_date, end_date, start_price, img, start_price + step AS current_price, categories.name AS category_name
//        FROM lots JOIN categories ON lots.category_id = categories.id
//        ORDER BY lots.create_date DESC;');
    $ads = fetch_all($link, $lots_sql);
    return $ads;
}

/**
 * Получение списка категорий
 * @param $link
 * @return array
 */
function get_categories ($link, $categories_sql) {
    $categories = fetch_all($link, $categories_sql);
    return $categories;
}

function get_lot_by_id ($link, $received_lot_id) {
    $lot_result = mysqli_query($link,
        "SELECT lots.id, create_date, lots.name AS lot_name, description, img, start_price + step AS current_price,
    end_date, step, author_id, winner_id, categories.name AS category_id
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE lots.id = $received_lot_id;
    ");
    // преобразование полученных данных в двумерный массив
    $adv = fetch_one($lot_result);
    return $adv;
}
// TODO переделать на шаблоны



