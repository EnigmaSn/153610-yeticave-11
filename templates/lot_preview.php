<li class="lots__item lot">
    <div class="lot__image">
        <img src="uploads/<?= (isset($lot['img']) ? esc($lot['img']) : null); ?>" width="350" height="260"
             alt="<?= (isset($lot['name']) ? esc($lot['name']) : null); ?>">
    </div>
    <div class="lot__info">
        <span class="lot__category">
            <?= (isset($lot['category']) ? esc($lot['category']) : null); ?>
        </span>
        <h3 class="lot__title"><a class="text-link" href="/lot.php/?id=<?= (isset($lot['id']) ? esc($lot['id']) : null); ?>"><?= (isset($lot['name']) ? esc($lot['name']) : null); ?></a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">
                    <?php
                    if (isset($lot['bet_count']) && $lot['bet_count'] > 0) {
                        echo esc($lot['bet_count']).
                            esc(get_noun_plural_form($lot['bet_count'], ' ставка',
                                ' ставки', ' ставок'));
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
