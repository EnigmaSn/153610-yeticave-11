<?php
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'title' => 'Страница лота',
        'categories' => $categories,
    ]
);
