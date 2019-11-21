<?php

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
