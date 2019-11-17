<?php
//$categories  = [
//    [
//        'name' => 'Доски и лыжи',
//        'url' => 'boards-and-skis',
//        'modifier' => 'boards'
//    ],
//    [
//        'name' => 'Крепления',
//        'url' => 'mounts',
//        'modifier' => 'attachment'
//    ],
//    [
//        'name' => 'Ботинки',
//        'url' => 'boots',
//        'modifier' => 'boots'
//    ],
//    [
//        'name' => 'Одежда',
//        'url' => 'clothes',
//        'modifier' => 'clothing'
//    ],
//    [
//        'name' => 'Инструменты',
//        'url' => 'tools',
//        'modifier' => 'tools'
//    ],
//    [
//        'name' => 'Разное',
//        'url' => 'other',
//        'modifier' => 'other'
//    ]
//];
//$ads = [
//    [
//        'name' => '2014 Rossignol District Snowboard',
//        'category' => $categories[0]['name'],
//        'price' => 10999,
//        'img_url' => 'lot-1.jpg',
//        'finish_date' => '2019-10-04',
//    ],
//    [
//        'name' => 'DC Ply Mens 2016/2017 Snowboard',
//        'category' => $categories[0]['name'],
//        'price' => 159999,
//        'img_url' => 'lot-2.jpg',
//        'finish_date' => '2019-12-19',
//    ],
//    [
//        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
//        'category' => $categories[1]['name'],
//        'price' => 8000,
//        'img_url' => 'lot-3.jpg',
//        'finish_date' => '2020-01-20',
//    ],
//    [
//        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
//        'category' => $categories[2]['name'],
//        'price' => 10999,
//        'img_url' => 'lot-4.jpg',
//        'finish_date' => '2020-03-03',
//    ],
//    [
//        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
//        'category' => $categories[3]['name'],
//        'price' => 7500,
//        'img_url' => 'lot-5.jpg',
//        'finish_date' => '2019-12-31',
//    ],
//    [
//        'name' => 'Маска Oakley Canopy',
//        'category' => $categories[5]['name'],
//        'price' => 5400,
//        'img_url' => 'lot-6.jpg',
//        'finish_date' => '2019-12-25',
//    ]
//];

$lots_sql = "
    SELECT lots.id  AS lot_id, lots.name AS lot_name, create_date,
    end_date, start_price, img, start_price + step AS current_price, 
    categories.name AS category_name
    FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    ORDER BY lots.create_date DESC;";
$categories_sql = "
    SELECT id, name, symbol_code FROM categories;";
