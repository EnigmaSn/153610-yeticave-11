<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('models/models.php');
require_once('functions.php');

$categories = get_categories($link);
$errors = [];

if (!empty($_POST)) {
    $fields = get_user_form_reg_data($_POST);
    $errors = validate_reg_form($link, $fields);

    // TODO trim (?)

    if (count($errors)) {
        $page_content = include_template(
            'sign_up.php',
            [
                'errors' => $errors,
                'categories' => $categories,
            ]
        );
    } else {
        $is_user_added = insert_user($link);
        // если пользователь добавлен в БД,
        // то перейти на страницу входа
        if ($is_user_added) {
            header("Location: sign_in.php");
        }
    }
} else {
    $page_content = include_template(
        'sign_up.php',
        [
            'categories' => $categories,
            'errors' => $errors,
        ]
    );
}
// закрыть доступ, если пользователь авторизован
if (isset($_SESSION['user'])) {
    http_response_code(403);
    $error = "Error 403 <br> Доступ запрещен";
    $page_content = include_template('error.php', [
        'categories' => $categories,
        'error'      => $error
    ]);
}
$layout_content = include_template(
    'layout.php',
[
        'page_content' => $page_content,
        'title' => 'Страница регистрации',
        'categories' => $categories,
        'flatpickr' => false
    ]
);

print($layout_content);
