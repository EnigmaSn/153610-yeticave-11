<?php
session_start();
$_SESSION = []; // обнуляем сессию
header("Location: /"); // переадресуем на главную
