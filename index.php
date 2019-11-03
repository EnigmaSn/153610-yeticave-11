<?php
    require_once('helpers.php');
    require_once('init.php');
    require_once('data.php');
    require_once('functions.php');

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
