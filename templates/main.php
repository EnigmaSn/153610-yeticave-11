<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['modifier']; ?>">
                <a class="promo__link" href="/pages/<?= $category['url']; ?>.html">
                    <?= $category['name']; ?>
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
                    <img src="img/<?= $adv['img_url']; ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                        <span class="lot__category">
                            <?= $adv['category']; ?>
                        </span>
                    <h3 class="lot__title">
                        <a class="text-link" href="#">
                            <?= $adv['name']; ?>
                        </a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost">
                                    <?= format_sum($adv['price']); ?>
                                </span>
                        </div>

                        <div class="lot__timer timer <?php
                            $hours = find_remaining_time($adv['finish_date'])['hours'];
                            if ($hours <= 0) {
                                echo 'timer--finishing';
                            }
                            ?>">
                            <?=
                                find_remaining_time($adv['finish_date'])['hours'] . ':' .
                                find_remaining_time($adv['finish_date'])['minutes'];
                            ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>