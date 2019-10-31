<?php
    require_once('helpers.php');

    $is_auth = rand(0, 1);

    $user_name = 'Anna';

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
            'img_url' => 'lot-1.jpg'
        ],
        [
            'name' => 'DC Ply Mens 2016/2017 Snowboard',
            'category' => $categories[0]['name'],
            'price' => 159999,
            'img_url' => 'lot-2.jpg'
        ],
        [
            'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
            'category' => $categories[1]['name'],
            'price' => 8000,
            'img_url' => 'lot-3.jpg'
        ],
        [
            'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
            'category' => $categories[2]['name'],
            'price' => 10999,
            'img_url' => 'lot-4.jpg'
        ],
        [
            'name' => 'Куртка для сноуборда DC Mutiny Charocal',
            'category' => $categories[3]['name'],
            'price' => 7500,
            'img_url' => 'lot-5.jpg'
        ],
        [
            'name' => 'Маска Oakley Canopy',
            'category' => $categories[5]['name'],
            'price' => 5400,
            'img_url' => 'lot-6.jpg'
        ]
    ];

    function format_sum(float $number) {
        $number = ceil($number);
        $ruble_symbol = '<b class="rub">р</b>';

        if ($number >= 1000) {
            $number = number_format($number, 0, '', ' ');
            return $number . $ruble_symbol;
        }

        return $number . $ruble_symbol;
    };
?>

<?php
    $page_content = include_template(
        'main.php',
        [
            '$categories' => $categories,
            '$ads' => $ads
        ]
    );
    $layout_content = include_template(
        'layout.php',
        [
            '$page_content' => $page_content,
            '$user_name' => $user_name,
            '$title' => 'Yeti Cave - Главная страница'
        ]
    );
    print($layout_content);
?>
