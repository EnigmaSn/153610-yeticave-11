<?php
require_once('helpers.php');
require_once('init.php');
require_once('data.php');
require_once('functions.php');
require_once('db.php');
require_once('models/models.php');

$categories = get_categories($link);
$categories_id = array_column($categories, 'id');
$errors = [];

// проверка на отправку методом POST (сценарий вызван отправкой формы)
if (!empty($_POST)) {
    $lot_data = get_lot_form_data($_POST);
    $file_data = $_FILES['lot-image'];
    $errors = validate_lot_form($lot_data, $file_data, $categories_id);

    if (count($errors)) {
        $page_content = include_template(
            'add.php',
            [
                'fields' => $lot_data,
                'errors' => $errors,
                'categories' => $categories,
            ]
        );
    } else {
        $lot_data['lot-file'] = save_lot_img($file_data); // путь загруженного файла
        $lot_id = insert_lot($link, $lot_data);
        // если результат запроса выполнен = получен id
        if ($lot_id) {
            // переадресовать пользователя на страницу просмотра этого лота
            header("Location: lot.php?id=" . $lot_id);
        }
    }

} else {
    $page_content = include_template(
        'add.php',
        [
            'categories' => $categories,
            'errors' => $errors
        ]
    );
}

$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Страница добавления лота',
        'is_auth' => $is_auth,
        'categories' => $categories,
        'flatpickr' => true
    ]
);
print($layout_content);
