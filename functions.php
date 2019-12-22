<?php

/**
 * Форматирование цены
 * @param float $price - изначальная цена
 * @return string - измененная цена
 */
function format_sum(float $price) {
    $price = ceil($price);
    //$ruble_symbol = '<b class="rub">р</b>';

    $price = number_format($price, 0, '', ' ');
    //return $price . $ruble_symbol;
    return $price;
};

/**
 * Экранирует исходную строку
 * @param {string} $str — исходная строка, которую надо экранировать
 * @return string — экранированная строка
 */
function esc($data) {
    $str = htmlspecialchars($data);
    return $str;
}

/**
 * Функция возвращает количество часов и минут, оставшихся до конкретной даты
 * @param $date - дата, до которой нужно посчитать количество оставшегося времени
 * @return array - массив - массива из часов и минут, оставшихся до окончания
 */
function find_remaining_time($date) {
    // часовой пояс установлен в init.php

    // первый способ
//    $current_date = time(); // текущая метка времени
//    $date = strtotime($date); // метка времени лота
//    $remaining_time_arr = [];
//    if ($current_date < $date) {
//        $remaining_time = $date - $current_date; // разница временных меток
//        $remaining_time_hours = floor($remaining_time / SECONDS_PER_HOURS);
//        $remaining_time_minutes = (floor($remaining_time / SECONDS_PER_MINUTES)) - ($remaining_time_hours * SECONDS_PER_MINUTES);
//        $remaining_time_arr = [
//            'hours' => $remaining_time_hours,
//            'minutes' => $remaining_time_minutes,
//            'status' => true
//        ];
//    } else {
//        $remaining_time_arr = [
//            'status' => false,
//        ];
//    }

    // второй способ
    $current_date = date_create("now");
    $date = date_create($date);
    $remaining_time_arr = [];
    if ($current_date < $date) {
        $remaining_time = date_diff($date, $current_date);
        $remaining_days = date_interval_format($remaining_time, '%a');
        $remaining_hours = date_interval_format($remaining_time, '%h');
        $remaining_minutes = date_interval_format($remaining_time, '%i');
        $remaining_total_hours = $remaining_days * HOURS_PER_DAY + $remaining_hours;
        $remaining_time_arr = [
            'hours' => $remaining_total_hours,
            'minutes' => $remaining_minutes,
            'status' => true,
        ];
    } else {
        $remaining_time_arr = [
            'hours' => 0,
            'minutes' => 0,
            'status' => false,
        ];
    }


    //$remaining_time = implode(':', $remaining_time_arr);
    // TODO get_noun_plural_form
    return $remaining_time_arr;
//    return $remaining_time;
}

/**
 * Рассчитывает время, прошедшее с момента создания ставки
 * @param $bet_time - время создания ставки
 * @return int
 */
function get_elapsed_time($bet_time) {
    // TODO доделать
    $current_date = time();
    $diff = $current_date - $bet_time;

    return $diff;
}

/**
 * Возврат данных из неотправленной формы
 * @param $name - имя поля
 * @return mixed - значение переменной в случае успеха
 */
function get_post_val($name) {
    $value = filter_input(INPUT_POST, $name);
    return esc($value);
}

function is_correct_length($name, $min, $max) {
    $len = strlen($name);
    if ($len < $min || $len > $max) {
        return "Значение  должно быть от $min до $max символов";
    }
    return null;
}

// GETTERS
function get_lot_form_data($lot_data) : array {
    // возврат значений для всех указанных полей
    $lot_data = filter_input_array(INPUT_POST,
        [
            'lot-name' => FILTER_DEFAULT, // без фильтра
            'category' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT,
            'lot-rate' => FILTER_DEFAULT,
            'lot-step' => FILTER_DEFAULT,
            'lot-date' => FILTER_DEFAULT
        ],
        true // добавляет в результат отсутствующие ключи со значением null
    );
    return $lot_data;
}

function get_user_form_reg_data(array $fields) : array {
    $fields = filter_input_array(INPUT_POST,
        [
            'email'=> FILTER_DEFAULT,
            'password' => FILTER_DEFAULT,
            'name' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT
        ], true);
    return $fields;
}
function get_login_form_data(array $fields) : array {
    $fields = filter_input_array(INPUT_POST,
        [
            'email'=> FILTER_DEFAULT,
            'password' => FILTER_DEFAULT
        ], true);
    return $fields;
}
function get_add_bet_form_data (array $fields) : array {
    $fields = filter_input_array(INPUT_POST,
        [
            'cost' => FILTER_DEFAULT
        ], true);
    return $fields;
}


// VALIDATION
function validate_bet_form($bet_data, $min_next_bet, $author_id) : array {
    $errors = [];
    $errors['cost'] = validate_bet($bet_data, $min_next_bet, $author_id );
    $errors = array_filter($errors);
    return  $errors;
}
function validate_bet($data, $min_next_bet, $author_id) {

    if (!is_int($data)) {
        return "Ставка должна быть числом";
    }
    if ($data < $min_next_bet) {
        return "Новая ставка должна быть больше $min_next_bet";
    }

    if ($author_id === (int) $_SESSION['user']['id']) {
        return "Нельзя добавлять ставку к своим лотам";
    }
    if (!$data) {
        return "Введите ставку";
    }
    // TODO не повторная ставка ???
    // проверять последнюю ставку на повтор user id

    return null;
}

function validate_lot_form(array $lot_data, $file_data, array $categories_id) : array {
    $errors = [];
    $required_fields = [
        'lot-name',
        'category',
        'message',
        'lot-image',
        'lot-rate',
        'lot-step',
        'lot-date'
    ];
    $errors['lot-name'] = validate_lot_name($lot_data['lot-name']);
    $errors['category'] = validate_lot_category($lot_data['category'], $categories_id);
    $errors['message'] = validate_lot_message($lot_data['message']);
    //$errors['lot-image'];
    $errors['lot-rate'] = validate_lot_rate($lot_data['lot-rate']);
    $errors['lot-step'] = validate_lot_step($lot_data['lot-step']);
    $errors['lot-date'] = validate_lot_date($lot_data['lot-date']);
    $errors['lot-file'] = validate_lot_file($file_data);

    foreach ($lot_data as $field_name => $field_value) {
        // проверка обязательных полей на пустоту
        if (in_array($field_name, $required_fields) && empty($field_value)) {
            $errors[$field_name] = "Поле $field_name необходимо заполнить";
        }
    }

    $errors = array_filter($errors);
    return  $errors;
}

function validate_lot_category(string $category, array $categories) {
    if (!in_array($category, $categories)) {
        return "Такой категории не существует";
    }
    return null;
}
function validate_lot_name($data) {
    return is_correct_length($data, 3, 128);
}
function validate_lot_message($data) {
    return is_correct_length($data, 3, 3000);
}
function validate_lot_rate($data) {
    if (!$data || $data <= 0) {
        return "Начальная цена должна быть числом больше ноля";
    }
    return null;
}
function validate_lot_step($data) {
    if (!$data || $data <= 0) {
        return "Шаг ставки должен быть числом больше ноля";
    }
    return null;
}
function validate_lot_date($data) {
    $format = is_date_valid($data); // boolean
    $current_date = date_create("now");
    $current_date = date_format($current_date, 'Y-m-d');
    if (!$format || $data < $current_date) {
        return "Неверная дата";
    }
    return null;
}
function validate_lot_file(array $data) {
    if ($data['size'] === 0) {
        return "Загрузите изображение";
    }
    $path = $data['tmp_name'];
    $file_type = mime_content_type($path);
    $allowed_types = [
        'image/png',
        'image/jpeg'
    ];
    if (!in_array($file_type, $allowed_types)) {
        return "Необходимо загрузить файл в формате PNG, JPEG либо JPG";
    }
    return null;
}

function validate_reg_form(mysqli $link, array $fields) : array {
    $errors = [];
    $required_fields = [
        'email',
        'password',
        'name',
        'message'
    ];
    $errors['email'] = validate_email($link, $fields['email']);
    foreach ($fields as $field_name => $field_value) {
        // проверка обязательных полей на пустоту
        if (in_array($field_name, $required_fields) && empty($field_value)) {
            $errors[$field_name] = "Поле $field_name необходимо заполнить";
        }
    }
    $errors = array_filter($errors);
    return $errors;
}
function validate_login_form(mysqli $link, array $fields) : array {
    $errors = [];
    $required_fields = [
        'email',
        'password'
    ];
    $errors['email'] = validate_email_exist($link, $fields['email']);
    $errors['password'] = validate_login_password($link, $fields['email'], $fields['password']);

    foreach ($fields as $field_name => $field_value) {
        if (in_array($field_name, $required_fields) && empty($field_value)) {
            $errors[$field_name] = "Поле $field_name необходимо заполнить";
        }
    }
    $errors = array_filter($errors);
    return $errors;
}
//function validate_bet_form(mysqli $link, array $fields) : array {
//    $errors = [];
//    $required_fields = [
//        'cost',
//    ];
//    $errors['cost'] = validate_cost($link, $fields['cost']);
//
//    foreach ($fields as $field_name => $field_value) {
//        if (in_array($field_name, $required_fields) && empty($field_value)) {
//            $errors[$field_name] = "Поле $field_name необходимо заполнить";
//        }
//    }
//    $errors = array_filter($errors);
//
//    return $errors;
//}
function validate_cost(mysqli $link, string $cost) {
    if (!is_int($cost)) {
        return "Ставка должна быть числом";
    }
    return null;
}
function validate_email_exist(mysqli $link, string $email) : ?string {
    $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email_is_valid) {
        return "Введите валидный email";
    }
    if(!is_null(is_correct_length($email, 3, 128))) {
        return is_correct_length($email, 3, 128);
    }
    $email_exists = check_email($link, $email);
    if (!$email_exists) {
        return "Данный email не зарегистрирован";
    }
    return null;
}
function validate_login_password(mysqli $link, string $email, string $password) : ?string {
    $password_from_db = get_password($link, $email)['password'];
    if(!is_null(is_correct_length($email, 3, 128))) {
        return is_correct_length($email, 3, 128);
    }
    if (!password_verify($password, $password_from_db)) {
        return "Вы ввели неверный пароль";
    }
    return null;
}
function validate_email(mysqli $link, $email) {
    $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(!is_null(is_correct_length($email, 3, 128))) {
        return is_correct_length($email, 3, 128);
    }
    if (!$email_is_valid) {
        return "Введите валидный email";
    }
    $email_is_double = check_email($link, $email);
    if ($email_is_double) {
        return "Такой email уже зарегистрирован";
    }
    return null;
}

// OTHER
function save_lot_img(array $data) : string {
    var_dump($data);
    $tmp_file_name = $data['name'];
    $tmp_file_path = $data['tmp_name'];
    $file_ext = substr($tmp_file_name, strrpos($tmp_file_name, '.'));
    $file_unique_name = uniqid() . $file_ext;
    $file_new_full_name = "uploads/" . $file_unique_name;
    $is_moved = move_uploaded_file($tmp_file_path, $file_new_full_name);
    if ($is_moved) {
        //return $file_new_full_name;
        return $file_unique_name;
    }

    return "Файл не перемещен";
}
function get_bet_timeleft(string $bets_creation_time): string
{
    $now = time();
    $bet_time = strtotime($bets_creation_time);
    $diff_time = $now - $bet_time;
    $time_left = '';
    if ($diff_time < 59) {
        $time_left = $diff_time.' '.get_noun_plural_form($diff_time,
                'секунда', 'секунды', 'секунд').' назад';
    } elseif ($diff_time < 3600) {
        $diff_time = floor($diff_time / 60);
        $time_left = $diff_time.' '.get_noun_plural_form($diff_time,
                'минута', 'минуты', 'минут').' назад';
    } elseif ($diff_time < 86400) {
        $diff_time = floor($diff_time / 3600);
        $time_left = $diff_time.' '.get_noun_plural_form($diff_time,
                'час', 'часа', 'часов').' назад';
    } elseif ($diff_time < 172800) {
        $diff_time = floor($diff_time / 86400);
        $time_left = date('Вчера в H:i', $bet_time);
    } elseif ($diff_time > 86400) {
        $time_left = date('d.m.y в H:i', $bet_time);
    }

    return $time_left;
}
