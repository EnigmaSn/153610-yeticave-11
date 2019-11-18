<?php
/**
 * Форматирование цены
 * @param float $price - изначальная цена
 * @return string - измененная цена
 */
function format_sum(float $price) {
    $price = ceil($price);
    $ruble_symbol = '<b class="rub">р</b>';

    $price = number_format($price, 0, '', ' ');
    return $price . $ruble_symbol;
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
function get_elapsed_time ($bet_time) {
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
function get_post_val ($name) {
    return filter_input(INPUT_POST, $name);
}

// Содержимое поля «начальная цена»
// должно быть числом больше нуля.
function validate_filled ($name) {
    if (empty($_POST[$name])) {
        return "<br>Поле $name должно быть заполнено";
    }
}
function validate_lot_rate ($name) {
    if ($_POST[$name] <= 0) {
        return "<br>Начальная цена должна быть больше ноля";
    }
}

// TODO доделать валидацию даты
function validate_lot_date ($name) {
    // TODO Содержимое поля «дата завершения»
    // должно быть датой в формате «ГГГГ-ММ-ДД»;
    $date = date_create_from_format('Y-m-d');
    if (!$date) {
        echo "Неверный формат даты";
    }
    // TODO Проверять, что указанная дата больше
    // текущей даты, хотя бы на один день.
}

// Содержимое поля «шаг ставки» должно быть целым числом больше ноля.
function validate_lot_step ($name) {
    if ($_POST[$name] <= 0)  {
        echo "<br>Шаг ставки должен быть больше ноля";
    }
}

function is_correct_length ($name, $min, $max) {
    $len = strlen($_POST[$name]);
    if ($len < $min || $len > $max) {
        return "Значение  должно быть от $min до $max символов";
    }
}
