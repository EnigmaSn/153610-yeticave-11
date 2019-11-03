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
 * Защита от XSS. Перевод специальных символов,
 * введенных пользователем в html сущности
 * @param $data - данные, введенные пользователем
 * @return string - преобразованное в строку значение без спецсимволов
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
    $remaining_time = date_diff($date, $current_date);
    $remaining_days = date_interval_format($remaining_time, '%a');
    $remaining_hours = date_interval_format($remaining_time, '%h');
    $remaining_minutes = date_interval_format($remaining_time, '%i');
    $remaining_total_hours = $remaining_days * HOURS_PER_DAY + $remaining_hours;
    $remaining_time_arr = [
        'hours' => $remaining_total_hours,
        'minutes' => $remaining_minutes,
    ];
    //$remaining_time = implode(':', $remaining_time_arr);
    // TODO get_noun_plural_form
    return $remaining_time_arr;
//    return $remaining_time;
}
