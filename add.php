<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');
require_once('models/models.php');

// в models
// получение списка категорий
$categories = get_categories($link);

$page_content = include_template(
    'add-main.php',
    [
        'categories' => $categories,

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
