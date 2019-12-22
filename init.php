<?php
session_start();
date_default_timezone_set('Europe/Moscow');

// данные для MySQL
$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'new_password', // как это хранить?
    'database' => 'yeticave',
];

// Константы
define('HOURS_PER_DAY', 24);
define('SECONDS_PER_HOURS', 3600);
define('SECONDS_PER_MINUTES', 60);
