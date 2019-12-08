<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('models/models.php');

// TODO вынести

// получение списка категорий
$categories = get_categories($link);
$errors = [];

if (!empty($_POST)) {
    //var_dump($_POST);
    $required_fields = [
        'email',
        'password',
        'name',
        'message'
    ];

    // TODO trim
    $rules = [
        'email' => function($link) {
            // если email валидный
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                // проверяем наличие в БД
                $email = check_email($link, $_POST['email']);
                if ($email) {
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

    foreach ($fields as $field_name => $field_value) {
        if (isset($rules[$field_name])) {
            $rule = $rules[$field_name];
            $errors[$field_name] = $rule($field_value);
        }

        if (in_array($field_name, $required_fields) && empty($field_value)) {
            $errors[$field_name] = "Поле $field_name необходимо заполнить";
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
        var_dump($errors);
    } else {
        // передевать не $_POST, а отдельные значения?
        // где именно хэшировать пароль?
        $user_data = [
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'],PASSWORD_DEFAULT),
            'name' => $_POST['name'],
            'contact' => $_POST['message']
        ];

        if (insert_user($link, $user_data)) {
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

$layout_content = include_template(
    'layout.php',
[
        'page_content' => $page_content,
        'title' => 'Страница регистрации',
        'is_auth' => $is_auth,
        'categories' => $categories,
        'flatpickr' => false
    ]
);

print($layout_content);
