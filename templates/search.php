<?= include_template('nav.php', ['categories' => $categories]) ?>

<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search_query; ?></span>»</h2>
        <?php if (is_array($lots)) :
            ; ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="uploads/<?= isset($lot['img']) ? esc($lot['img']) : null; ?>" width="350" height="260"
                                 alt="<?= isset($lot['name']) ? esc($lot['name']) : null; ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">
                                <?= isset($lot['category']) ? esc($lot['category']) : null; ?>
                            </span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php/?id=<?= isset($lot['id']) ? esc($lot['id']) : null; ?>"><?= isset($lot['name']) ? esc($lot['name']) : null; ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?php
                                    if (isset($lot['start_price'])) {
                                        echo format_sum($lot['start_price']);
                                    }
                                    ?></span>
                                </div>
                                <div class="lot__timer timer <?php
                                if (isset($lot['end_date'])) {
                                    $hours = find_remaining_time($lot['end_date'])['hours'];
                                    $minutes = find_remaining_time($lot['end_date'])['minutes'];
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
        <?php else : ?>
            <h3><?= $error; ?></h3>
        <?php endif; ?>
    </section>
    <?php if (count((array) $pages) > 1) {
        echo include_template('paginator.php', [
            'pages'    => $pages,
            'cur_page' => $cur_page,
            'param'    => 'search='.$query
        ]);
    }; ?>
</div>
