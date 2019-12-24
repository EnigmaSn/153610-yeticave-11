<?= include_template('nav.php', ['categories' => $categories]) ?>

<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach ($bets as $bet): ?>
        <?php $timer = get_timer_state($bet, $user_id, $win_bets); ?>
        <tr class="rates__item
            <?php $timer['class']; ?>">
            <td class=" rates__info">
                <div class="rates__img">
                    <img src="uploads/<?= $bet['img']; ?>" width="54" height="40"
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
                <div class="timer <?php $hours = find_remaining_time($bet['end_date'])['hours'];
                if ($hours === 0) {
                    echo 'timer--finishing';
                }
                ?>">
                    <?=
                    esc(find_remaining_time($bet['end_date'])['hours'] . ':' .
                        find_remaining_time($bet['end_date'])['minutes']);
                    ?>
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
    </table>
</section>
