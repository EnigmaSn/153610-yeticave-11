<?php
// подключаемся к базе данных
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$link) {
    die("Ошибка: Невозможно подключиться к MySQL: <br> " . mysqli_connect_error());
}

// устанавливаем кодировку
mysqli_set_charset($link, 'utf8');
