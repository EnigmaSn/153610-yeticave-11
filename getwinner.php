<?php
require_once('init.php');

$lots = get_lots_without_win($link);

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');
$mailer = new Swift_Mailer($transport);

foreach ($lots as $lot) {
    $winner = get_winner($link, $lot['id']);
    if (isset($winner['user_id'])) {
        add_winner_to_lot($link, $lot['id'], $winner['user_id']);
        $message = get_message($winner, $lot['id']);
        $result = $mailer->send($message);
    }
}
