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
