<?php
    require_once('helpers.php');

    // TODO вынести init.php
    $is_auth = rand(0, 1);
    $user_name = 'Anna';

    // TODO data.php
    $categories  = [
        [
            'name' => 'Доски и лыжи',
            'url' => 'boards-and-skis',
            'modifier' => 'boards'
        ],
        [
            'name' => 'Крепления',
            'url' => 'mounts',
            'modifier' => 'attachment'
        ],
        [
            'name' => 'Ботинки',
            'url' => 'boots',
            'modifier' => 'boots'
        ],
        [
            'name' => 'Одежда',
            'url' => 'clothes',
            'modifier' => 'clothing'
        ],
        [
            'name' => 'Инструменты',
            'url' => 'tools',
            'modifier' => 'tools'
        ],
        [
            'name' => 'Разное',
            'url' => 'other',
            'modifier' => 'other'
        ]
    ];
    $ads = [
        [
            'name' => '2014 Rossignol District Snowboard',
            'category' => $categories[0]['name'],
            'price' => 10999,
            'img_url' => 'lot-1.jpg',
        ],
        [
            'name' => 'DC Ply Mens 2016/2017 Snowboard',
            'category' => $categories[0]['name'],
            'price' => 159999,
            'img_url' => 'lot-2.jpg',
        ],
        [
            'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
            'category' => $categories[1]['name'],
            'price' => 8000,
            'img_url' => 'lot-3.jpg',
        ],
        [
            'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
            'category' => $categories[2]['name'],
            'price' => 10999,
            'img_url' => 'lot-4.jpg',
        ],
        [
            'name' => 'Куртка для сноуборда DC Mutiny Charocal',
            'category' => $categories[3]['name'],
            'price' => 7500,
            'img_url' => 'lot-5.jpg',
        ],
        [
            'name' => 'Маска Oakley Canopy',
            'category' => $categories[5]['name'],
            'price' => 5400,
            'img_url' => 'lot-6.jpg',
        ]
    ];

    // TODO functions.php
    /**
     * Форматирование цены
     * @param float $price - изначальная цена
     * @return string - измененная цена
     */
    function format_sum(float $price) {
        $price = ceil($price);
        $ruble_symbol = '<b class="rub">р</b>';

        $price = number_format($price, 0, '', ' ');
        return $price . $ruble_symbol;
    };

    // TODO functions.php
    /**
     * Защита от XSS. Перевод специальных символов,
     * введенных пользователем в html сущности
     * @param $data - данные, введенные пользователем
     * @return string - преобразованное в строку значение без спецсимволов
     */
    function esc($data) {
        $str = htmlspecialchars($data);
        return $str;
    }

    $page_content = include_template(
        'main.php',
        [
            'categories' => $categories,
            'ads' => $ads,
        ]
    );
    $layout_content = include_template(
        'layout.php',
        [
            'page_content' => $page_content,
            'user_name' => $user_name,
            'title' => 'Yeti Cave - Главная страница',
            'is_auth' => $is_auth,
            'categories' => $categories,
        ]
    );
    print($layout_content);
