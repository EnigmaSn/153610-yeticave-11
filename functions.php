<?php

/**
 * Форматирование цены
 * @param float $price - изначальная цена
 * @return string - измененная цена
 */
function format_sum(float $price)
{
    $price = ceil($price);

    $price = number_format($price, 0, '', ' ');
    return $price;
}

/**
 * Экранирует исходную строку
 * @param {string} $str — исходная строка, которую надо экранировать
 * @return string — экранированная строка
 */
function esc($data)
{
    $str = htmlspecialchars($data);
    return $str;
}

/**
 * Функция возвращает количество часов и минут, оставшихся до конкретной даты
 * @param $date - дата, до которой нужно посчитать количество оставшегося времени
 * @return array - массив - массива из часов и минут, оставшихся до окончания
 */
function find_remaining_time($date)
{
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

    return $remaining_time_arr;
}

/**
 * Рассчитывает время, прошедшее с момента создания ставки
 * @param $bet_time - время создания ставки
 * @return int
 */
function get_elapsed_time($bet_time)
{
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
function get_post_val($name)
{
    $value = filter_input(INPUT_POST, $name);
    return esc($value);
}

/**
 * Проверка корректности длины введенного значения
 * @param $name
 * @param $min
 * @param $max
 * @return string|null
 */
function is_correct_length($name, $min, $max)
{
    $len = strlen($name);
    if ($len < $min || $len > $max) {
        return "Значение  должно быть от $min до $max символов";
    }
    return null;
}

// GETTERS
/**
 * Получение списка всех полей для формы добавления лота
 * @param $lot_data
 * @return array
 */
function get_lot_form_data($lot_data): array
{
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

/**
 * Получение списка всех полей для формы регистрации
 * @param array $fields
 * @return array
 */
function get_user_form_reg_data(array $fields): array
{
    $fields = filter_input_array(INPUT_POST,
        [
            'email' => FILTER_DEFAULT,
            'password' => FILTER_DEFAULT,
            'name' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT
        ], true);
    return $fields;
}

/**
 * Получение списка всех полей для формы входа
 * @param array $fields
 * @return array
 */
function get_login_form_data(array $fields): array
{
    $fields = filter_input_array(INPUT_POST,
        [
            'email' => FILTER_DEFAULT,
            'password' => FILTER_DEFAULT
        ], true);
    return $fields;
}

/**
 * Получение списка всех полей для формы добавления ставки
 * @param array $fields
 * @return array
 */
function get_add_bet_form_data(array $fields): array
{
    $fields = filter_input_array(INPUT_POST,
        [
            'cost' => FILTER_DEFAULT
        ], true);
    return $fields;
}

// VALIDATION
/**
 * Валидация формы добавления ставки
 * @param $bet_data
 * @param $min_next_bet
 * @param $author_id
 * @return array
 */
function validate_bet_form($bet_data, $min_next_bet, $author_id): array
{
    $errors = [];
    $errors['cost'] = validate_bet($bet_data, $min_next_bet, $author_id);
    $errors = array_filter($errors);
    return $errors;
}

/**
 * Валидация поля ставки
 * @param $data
 * @param $min_next_bet
 * @param $author_id
 * @return string|null
 */
function validate_bet($data, $min_next_bet, $author_id)
{

    if (!is_int($data)) {
        return "Ставка должна быть числом";
    }
    if ($data < $min_next_bet) {
        return "Новая ставка должна быть больше $min_next_bet";
    }

    if ($author_id === (int)$_SESSION['user']['id']) {
        return "Нельзя добавлять ставку к своим лотам";
    }
    if (!$data) {
        return "Введите ставку";
    }
    // TODO не повторная ставка ???
    // проверять последнюю ставку на повтор user id

    return null;
}

/**
 * Валидация формы добавления лота
 * @param array $lot_data
 * @param $file_data
 * @param array $categories_id
 * @return array
 */
function validate_lot_form(array $lot_data, $file_data, array $categories_id): array
{
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
    return $errors;
}

/**
 * Валидация поля "Категория" в форме добавления лота
 * @param string $category
 * @param array $categories
 * @return string|null
 */
function validate_lot_category(string $category, array $categories)
{
    if (!in_array($category, $categories)) {
        return "Такой категории не существует";
    }
    return null;
}

/**
 * Валидация поля "Имя лота" в форме добавления лота
 * @param $data
 * @return string|null
 */
function validate_lot_name($data)
{
    return is_correct_length($data, 3, 128);
}

/**
 * Валидация поля "Описание" в форме добавления лота
 * @param $data
 * @return string|null
 */
function validate_lot_message($data)
{
    return is_correct_length($data, 3, 3000);
}

/**
 * Валидация поля "Стартовая цена" в форме добавления лота
 * @param $data
 * @return string|null
 */
function validate_lot_rate($data)
{
    if (!$data || $data <= 0) {
        return "Начальная цена должна быть числом больше ноля";
    }
    return null;
}

/**
 * Валидация поля "Шаг ставки" в форме добавления лота
 * @param $data
 * @return string|null
 */
function validate_lot_step($data)
{
    if (!$data || $data <= 0) {
        return "Шаг ставки должен быть числом больше ноля";
    }
    return null;
}

/**
 * Валидация поля "Дата окончания лота" в форме добавления лота
 * @param $data
 * @return string|null
 */
function validate_lot_date($data)
{
    $format = is_date_valid($data); // boolean
    $current_date = date_create("now");
    $current_date = date_format($current_date, 'Y-m-d');
    if (!$format || $data < $current_date) {
        return "Неверная дата";
    }
    return null;
}

/**
 * Валидация загруженного файла в форме добавления лота
 * @param array $data
 * @return string|null
 */
function validate_lot_file(array $data)
{
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

/**
 * Валидация формы регистрации
 * @param mysqli $link
 * @param array $fields
 * @return array
 */
function validate_reg_form(mysqli $link, array $fields): array
{
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

/**
 * Валидация формы входа
 * @param mysqli $link
 * @param array $fields
 * @return array
 */
function validate_login_form(mysqli $link, array $fields): array
{
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

/**
 * Валидация поля email в форме входа и проверка существования email в БД
 * @param mysqli $link
 * @param string $email
 * @return string|null
 */
function validate_email_exist(mysqli $link, string $email): ?string
{
    $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email_is_valid) {
        return "Введите валидный email";
    }
    if (!is_null(is_correct_length($email, 3, 128))) {
        return is_correct_length($email, 3, 128);
    }
    $email_exists = check_email($link, $email);
    if (!$email_exists) {
        return "Данный email не зарегистрирован";
    }
    return null;
}

/**
 * Валидация пароля в ворме входа
 * @param mysqli $link
 * @param string $email
 * @param string $password
 * @return string|null
 */
function validate_login_password(mysqli $link, string $email, string $password): ?string
{
    $password_from_db = get_password($link, $email)['password'];
    if (!is_null(is_correct_length($email, 3, 128))) {
        return is_correct_length($email, 3, 128);
    }
    if (!password_verify($password, $password_from_db)) {
        return "Вы ввели неверный пароль";
    }
    return null;
}

/**
 * Валидация поля email в форме регистрации
 * @param mysqli $link
 * @param $email
 * @return string|null
 */
function validate_email(mysqli $link, $email)
{
    $email_is_valid = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!is_null(is_correct_length($email, 3, 128))) {
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
/**
 * Сохранение изображение и возврат имени файла
 * @param array $data
 * @return string - новое имя файла с расширением
 */
function save_lot_img(array $data): string
{
    $tmp_file_name = $data['name'];
    $tmp_file_path = $data['tmp_name'];
    $file_ext = substr($tmp_file_name, strrpos($tmp_file_name, '.'));
    $file_unique_name = uniqid() . $file_ext;
    $file_new_full_name = "uploads/" . $file_unique_name;
    $is_moved = move_uploaded_file($tmp_file_path, $file_new_full_name);
    if ($is_moved) {
        return $file_unique_name;
    }

    return "Файл не перемещен";
}

/**
 * Время, когда была сделана ставка
 * @param string $bets_creation_time
 * @return string
 */
function get_bet_timeleft(string $bets_creation_time): string
{
    $now = time();
    $bet_time = strtotime($bets_creation_time);
    $diff_time = $now - $bet_time;
    $time_left = '';
    if ($diff_time < 59) {
        $time_left = $diff_time . ' ' . get_noun_plural_form($diff_time,
                'секунда', 'секунды', 'секунд') . ' назад';
    } elseif ($diff_time < 3600) {
        $diff_time = floor($diff_time / 60);
        $time_left = $diff_time . ' ' . get_noun_plural_form($diff_time,
                'минута', 'минуты', 'минут') . ' назад';
    } elseif ($diff_time < 86400) {
        $diff_time = floor($diff_time / 3600);
        $time_left = $diff_time . ' ' . get_noun_plural_form($diff_time,
                'час', 'часа', 'часов') . ' назад';
    } elseif ($diff_time < 172800) {
        $diff_time = floor($diff_time / 86400);
        $time_left = date('Вчера в H:i', $bet_time);
    } elseif ($diff_time > 86400) {
        $time_left = date('d.m.y в H:i', $bet_time);
    }

    return $time_left;
}

/**
 * Состояние торгов (в процессе, окончены, заканчиваются)
 * @param array $lot
 * @param int $user_id
 * @param array $win_bets
 * @return array
 */
function get_timer_state(array $lot, int $user_id = 0, $win_bets = []): array
{
    $time_remaining = get_time_remaining($lot['end_date']);
    $timer = [
        'state' => '',
        'message' =>
            sprintf("%02d", $time_remaining['d']) . ':'
            . sprintf("%02d", $time_remaining['h']) . ':'
            . sprintf("%02d", $time_remaining['m']),
        'class' => ''
    ];

    if ($time_remaining['diff'] === 0) {
        $timer['state'] = 'timer--end';
        $timer['message'] = 'Торги окончены';
        $timer['class'] = 'rates__item--end';
        if (isset($lot['bet_id']) and in_array($lot['bet_id'], $win_bets)) {
            $timer['state'] = 'timer--win';
            $timer['message'] = 'Ставка выиграла';
            $timer['class'] = 'rates__item--win';
        }
    } elseif ($time_remaining['diff'] < 3600) {
        $timer['state'] = 'timer--finishing';
    }

    return $timer;
}

/**
 * Оставшееся до истечения торгов время
 * @param string $time
 * @return array
 */
function get_time_remaining(string $time): array
{
    $time_now = time();
    $time_end = strtotime($time);
    $time_diff = $time_end - $time_now;
    if ($time_diff < 0) {
        $time_diff = 0;
    }

    $time_remaining = [
        'h' => floor(($time_diff % 86400) / 3600),
        'm' => floor(($time_diff % 3600) / 60),
        'd' => floor($time_diff / 86400),
        'diff' => $time_diff
    ];

    return $time_remaining;
}

/**
 * Письмо победителю торгов
 * @param array $winner
 * @param int $lot_id
 * @return Swift_Message
 */
function get_message(array $winner, int $lot_id): Swift_Message
{
    $msg_content = include_template('email.php', [
        'winner' => $winner,
        'lot_id' => $lot_id
    ]);

    $message = (new Swift_Message())
        ->setSubject('Ваша ставка победила')
        ->setFrom(['keks@phpdemo.ru' => 'Yeticave'])
        ->setTo([$winner['email'] => $winner['user_name']])
        ->setBody($msg_content, 'text/html');

    return $message;
}

/**
 * Функция-матка для добавления ставки
 * @param $bet_from_form
 * @param $errors
 * @param $categories
 * @param $adv
 * @param $bets
 * @param $link
 * @param $received_lot_id
 * @param $user_id
 */
function add_bet($bet_from_form, $errors, $categories, $adv, $bets, $link, $received_lot_id, $user_id)
{
    if (count($errors)) {
        $page_content = include_template(
            'lot.php',
            [
                'categories' => $categories,
                'adv' => $adv,
                'bets' => $bets,
                'errors' => $errors ?? null
            ]
        );
    } else {
        $bet_added = insert_bet($link, (int)$bet_from_form, (int)$received_lot_id, (int)$user_id, $bet_from_form);

        if ($bet_added) {
            $lot = get_lot_by_id($link, $received_lot_id);
            $bets = get_bets_for_lot($link, $received_lot_id);

            header("Location: lot.php?id=" . $_GET['id']);
        } else {
            echo "Ставка НЕ добавлена";
        }
    }
}
