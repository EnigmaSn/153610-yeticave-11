<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= esc($category['symbol_code']); ?>">
                <a class="promo__link" href="/all-lots.php?catid=<?= $category['id']; ?>">
                    <?= esc($category['name']); ?>
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
                    <img src="uploads/<?= esc($adv['img']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                        <span class="lot__category">
                            <?= esc($adv['category_name']); ?>
                        </span>
                    <h3 class="lot__title">
                        <a class="text-link" href="lot.php?id=<?= $adv['lot_id']; ?>">
                            <?= esc($adv['lot_name']); ?>
                        </a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                    <?php
                                    $current_price = $adv['max_bet'] ??
                                        $adv['current_price'];
                                    echo esc(format_sum($current_price)); ?> <b class="rub">р</b>
                                </span>
                        </div>

                        <div class="lot__timer timer <?php
                            $hours = find_remaining_time($adv['end_date'])['hours'];
                            if ($hours === 0) {
                                echo 'timer--finishing';
                            }
                            ?>">
                            <?=
                            esc(find_remaining_time($adv['end_date'])['hours'] . ':' .
                                find_remaining_time($adv['end_date'])['minutes']);
                            ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
