<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= (isset($winner['user_name']) ? esc($winner['user_name']) : null); ?></p>
<p>Ваша ставка для лота <a href="http:\\yeticave\lot.php?id=<?= $lot_id ?>">
        <?= $winner['lot_name'] ?></a> победила.</p>
<p>Перейдите по ссылке <a
        href="http:\\yeticave\bets.php?id=<?= (isset($winner['user_id']) ? esc($winner['user_id']) : null); ?>
">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
