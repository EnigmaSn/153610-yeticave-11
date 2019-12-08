<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('models/models.php');

// TODO вынести
// получение списка категорий
$categories = get_categories($link);

if (!empty($_POST)) {
    //var_dump($_POST);
    $required_fields = [
        'email',
        'password',
        'name',
        'message'
    ];

    $errors = [];
    $rules = [
        'email' => function($link) {
            // если email валидный
            if (filter_var($_POST, FILTER_VALIDATE_EMAIL)) {
                // проверяем наличие в БД
                $email = get_email($link, $_GET['email']);
                if (is_null($email)) {
                    return "Такой email уже существует";
                }
            } else {
                return "Введите валидный email";
            };
        },
        'password' => null,
        'name' => null,
        'message' => null
    ];

    $fields = filter_input_array(INPUT_POST,
    [
        'email'=> FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT,
        'message' => FILTER_DEFAULT
    ], true);

    foreach ($fields as $key => $value) {
        if (isset($rules['key'])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required_fields) && empty($value)) {
            $errors[$key] = "Поле $key необходимо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template(
            'sign_up.php',
            [
                'fields' => $fields,
                'errors' => $errors,
                'categories' => $categories,
            ]
        );
    }
} else {
    // передевать не $_POST, а отдельные значения?
    // где именно хэшировать пароль?
    $user_data = [
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'],PASSWORD_DEFAULT),
        'name' => $_POST['name'],
        'contact' => $_POST['message']
    ];
    if (insert_user($link, $_POST)) {
        header("Location: sign_in.php");
    }
    $page_content = include_template(
        'sign_up.php',
        [
            'categories' => $categories,
        ]
    );
}

$layout_content = include_template(
    'layout.php',
[
        'page_content' => $page_content,
        'title' => 'Страница регистрации',
        'is_auth' => false,
        'categories' => $categories,
        'flatpickr' => false
    ]
);
print($layout_content);
