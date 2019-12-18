<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('models/models.php');
require_once('functions.php');

$categories = get_categories($link);
$search_query = $_GET['search'] ?? '';
$lots_count = get_lots_count($link, $search_query);

if (is_int($lots_count)) {
    $lots = get_searching_lots($link, $search_query);
    $page_content = include_template('search.php', [
        'categories' => $categories,
        'lots' => $lots,
        'search_query' => $search_query
    ]);
} else {
    $page_content = include_template('search.php', [
        'categories' => $categories,
        'lots' => null,
        'search_query' => $search_query,
        'error' => $lots_count
    ]);
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'title' => 'Поиск',
        'categories' => $categories,
        'flatpickr' => false
    ]
);

print($layout_content);
