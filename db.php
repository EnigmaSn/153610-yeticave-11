<?php
// подключаемся к базе данных
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$link) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
} else {
    print("Соединение установлено успешно");
}

// устанавливаем кодировку
mysqli_set_charset($link, 'utf8');
