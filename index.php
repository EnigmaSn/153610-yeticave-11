<?php
    require_once('helpers.php');
    require_once('init.php');
    require_once('functions.php');
    require_once('models/models.php');

    $ads = get_lots($link); // получение списка новых лотов
    $categories = get_categories($link); // получение списка категорий

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
            //'user_name' => $user_name,
            'title' => 'Yeti Cave - Главная страница',
            'categories' => $categories,
            'flatpickr' => false
        ]
    );
    print($layout_content);
