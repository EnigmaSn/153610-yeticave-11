<li class="lots__item lot">
    <div class="lot__image">
        <img src="uploads/<?= $lot['img']; ?>" width="350" height="260"
             alt="<?= $lot['name']; ?>">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?= $lot['category']; ?></span>
        <h3 class="lot__title"><a class="text-link"
                                  href="/lot.php/?id=<?= $lot['id']; ?>"><?= $lot['name']; ?></a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">
                    <?php
                    if ($lot['bet_count'] > 0) {
                        echo $lot['bet_count'].
                            get_noun_plural_form($lot['bet_count'], ' ставка',
                                ' ставки', ' ставок');
                    } else {
                        echo 'Стартовая цена';
                    }
                    ?>
                    </span>
                <span class="lot__cost">
                    <?php $current_price = $lot['max_bet'] ??
                        $lot['start_price'];
                    echo format_sum($current_price)
                        .' <b class="rub">р</b>'; ?>
                </span>
            </div>
            <?php $timer = get_timer_state($lot); ?>
            <div class="lot__timer timer
            <?= $timer['state']; ?>">
                <?= $timer['message']; ?>
            </div>
        </div>
    </div>
</li>
