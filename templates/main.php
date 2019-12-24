<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= isset($category['symbol_code']) ? esc($category['symbol_code']) : null; ?>">
                <a class="promo__link" href="/all-lots.php?catid=<?= isset($category['id']) ? esc($category['id']) : null; ?>">
                    <?= isset($category['name']) ? esc($category['name']) : null; ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($ads as $adv): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="uploads/<?= isset($adv['img']) ? esc($adv['img']) : null; ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                        <span class="lot__category">
                            <?= isset($adv['category_name']) ? esc($adv['category_name']) : null; ?>
                        </span>
                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?id=<?= isset($adv['lot_id']) ? esc($adv['lot_id']) : null; ?>">
                            <?= isset($adv['lot_name']) ? esc($adv['lot_name']) : null; ?>
                        </a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                    <?php
                                    if (isset($adv['max_bet']) || isset($adv['current_price'])) {
                                        $current_price = $adv['max_bet'] ??
                                            $adv['current_price'];
                                        echo esc(format_sum($current_price));
                                    }
                                     ?> <b class="rub">р</b>

                                </span>
                        </div>

                        <div class="lot__timer timer <?php
                            if (isset($adv['end_date'])) {
                                $hours = find_remaining_time($adv['end_date'])['hours'];
                                $minutes = find_remaining_time($adv['end_date'])['minutes'];

                                if ($hours === 0) {
                                    echo 'timer--finishing';
                                }
                            }
                            ?>">
                            <?= $hours . ":" . $minutes; ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
