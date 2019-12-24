<?php
require_once('helpers.php');
require_once('init.php');
require_once('models/models.php');
require_once('functions.php');

$cur_page = $_GET['page'] ?? 1;
$item_per_page = 9;
$categories = get_categories($link);
$search_query = $_GET['search'] ?? '';
$lots_count = get_lots_count($link, $search_query);

if (is_int($lots_count)) {
    $pages_count = intval(ceil($lots_count / $item_per_page));
    $offset = ($cur_page - 1) * $item_per_page;
    $pages = range(1, $pages_count);
    $lots = get_searching_lots($link, $search_query, $item_per_page, $offset);
    $page_content = include_template('search.php', [
        'categories' => $categories,
        'pages' => $pages,
        'cur_page' => $cur_page,
        'lots' => $lots,
        'search_query' => $search_query
    ]);
} else {
    $page_content = include_template('search.php', [
        'categories' => $categories,
        'pages' => 1,
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
