<?php
require_once('helpers.php');
require_once('init.php');
require_once('models/models.php');
require_once('functions.php');

$categories = get_categories($link);
$errors = [];

if (!empty($_POST)) {
    $fields = get_login_form_data($_POST);
    $errors = validate_login_form($link, $fields);

    if (count($errors)) {
        $page_content = include_template('/login.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        // если аутентификация прошла успешно,
        // открываем сессию и
        // направляем пользователя на главную страницу
        $user = get_user($link, $_POST['email']);
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    }
} else {
    $page_content = include_template(
        'login.php',
        [
            'categories' => $categories,
            'errors' => $errors,
        ]
    );
    // если пользователь уже залогинен
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'title' => 'Страница входа',
        'categories' => $categories,
        'flatpickr' => false
    ]
);

print($layout_content);
