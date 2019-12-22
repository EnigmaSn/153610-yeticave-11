<?= include_template('nav.php', ['categories' => $categories]) ?>

<section class="rates container">
    <h2>Мои ставки</h2>
    <?php foreach ($bets as $bet): ?>
        <?php //$timer = get_bet_timeleft($bet, $user_id, $win_bets); ?>
        <tr class="rates__item
            <?php //$timer['class']; ?>">
            <td class=" rates__info">
                <div class="rates__img">
                    <img src="<?= $bet['img']; ?>" width="54" height="40"
                         alt="<?= $bet['name']; ?>">
                </div>
                <h3 class="rates__title"><a
                        href="/lot.php?id=<?= $bet['lot_id']; ?>"><?= $bet['name']; ?></a>
                </h3>
            </td>
            <td class="rates__category">
                <?= $bet['category']; ?>
            </td>
            <td class="rates__timer">
                <div class="timer <?php //$timer['state']; ?>">
                    <?php //$timer['message']; ?>
                </div>
            </td>
            <td class="rates__price">
                <?= format_sum($bet['price'])
                .' р'; ?>
            </td>
            <td class="rates__time">
                <?= $bet['time_back']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</section>
