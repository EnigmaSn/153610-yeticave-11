<?php
require_once('helpers.php');
require_once('init.php');
require_once('functions.php');
require_once('models/models.php');

$categories = get_categories($link);
$errors = [];
$user_id = $_SESSION['user']['id'];

// если пользователь не авторизован
if (!$user_id) {
    http_response_code(404);
    $error = "Авторизуйтесь, чтобы посмотреть свои ставки";
    $page_content = include_template('error.php', [
        'categories' => $categories,
        'errors' => $errors
    ]);
}

$bets = get_bets_for_user($link, $user_id);

$page_content = include_template('my-bets.php', [
    'categories' => $categories,
    'bets' => $bets ?? null,
    'errors'      => $errors,
    'user_id'    => $user_id,
    'win_bets'   => $win_bets ?? []

]);

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'title' => "Мои ставки" ?? 'Ошибка',
        'categories' => $categories,
        'flatpickr' => false
    ]
);
print($layout_content);
