<?= include_template('nav.php', ['categories' => $categories]) ?>

<section class="lot-item container">
      <h2><?= esc($adv['lot_name']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="uploads/<?= $adv['img']; ?>" width="730" height="548" alt="<?= $adv['lot_name']; ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?= $adv['category_id']; ?></span></p>
          <p class="lot-item__description"><?= $adv['description'] ?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer
            <?php
            $hours = find_remaining_time($adv['end_date'])['hours'];
            $minutes = find_remaining_time($adv['end_date'])['minutes'];

            if ($hours === 0) {
                echo 'timer--finishing';
            };
            ?>">
                <?=
                    find_remaining_time($adv['end_date'])['hours'] . ':' .
                    find_remaining_time($adv['end_date'])['minutes'];
                ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost">
                    <?php $current_price = $adv['max_bet'] ??
                        $adv['current_price'];
                    echo format_sum($current_price); ?> <b class="rub">р</b>
                </span>
              </div>
              <div class="lot-item__min-cost">
Мин. ставка <span><?= format_sum($adv['min_next_bet']); ?> </span>
              </div>
            </div>

            <?php $form_error = count($errors) ? "form--invalid" : ""; ?>
            <form class="lot-item__form <?php if(!isset($_SESSION['user']) || $adv['author_id'] === (int) $_SESSION['user']['id']): ?>visually-hidden<?php endif; ?> <?= $form_error; ?>" action="/lot.php?id=<?= $adv['id']; ?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item <?php if(isset($errors['cost'])): ?>form__item--invalid<?php endif; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="<?= format_sum($adv['min_next_bet']); ?>">
                <span class="form__error"><?= $errors['cost'] ?? null; ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
            <table class="history__list">
                <?php foreach ($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= $bet['user_name']; ?></td>
                        <td class="history__price"><?= format_sum((float)$bet['price']); ?></td>
                        <td class="history__time">
                            <?php
                                echo date('y.m.d', strtotime($bet['date'])) . ' в ' . date('H:i', strtotime($bet['date']));
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </section>
