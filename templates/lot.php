<?= include_template('nav.php', ['categories' => $categories]) ?>

<section class="lot-item container">
      <h2>
          <?= (isset($adv['lot_name']) ? esc($adv['lot_name']) : null); ?>
      </h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="uploads/<?= (isset($adv['img']) ? esc($adv['img']) : null); ?>" width="730" height="548" alt="<?= (isset($adv['lot_name']) ? esc($adv['lot_name']) : null); ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?= (isset($adv['category_id']) ? esc($adv['category_id']) : null); ?></span></p>
          <p class="lot-item__description">
              <?= (isset($adv['description']) ? esc($adv['description']) : null); ?>
          </p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer
            <?php
            if (isset($adv['end_date'])) {
                $hours = find_remaining_time($adv['end_date'])['hours'];
                $minutes = find_remaining_time($adv['end_date'])['minutes'];

                if ($hours === 0) {
                    echo 'timer--finishing';
                };
            }
            ?>">
                <?= $hours . ":" . $minutes; ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost">
                    <?php
                        if (isset($adv['max_bet']) || isset($adv['current_price'])) {
                            $current_price = $adv['max_bet'] ?? $adv['current_price'];
                            echo format_sum($current_price);
                        }
                    ?> <b class="rub">р</b>
                </span>
              </div>
              <div class="lot-item__min-cost">
Мин. ставка <span><?php
                      if (isset($adv['min_next_bet'])) {
                        echo format_sum($adv['min_next_bet']);
                      } ?> </span>
              </div>
            </div>

            <?php $form_error = count($errors) ? "form--invalid" : ""; ?>
            <form class="lot-item__form <?php
            // TODO вынести в функцию условие
            if(!isset($_SESSION['user']) || $adv['author_id'] === (int) $_SESSION['user']['id']): ?>visually-hidden<?php endif; ?> <?= $form_error; ?>" action="/lot.php?id=<?= $adv['id']; ?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item <?php if(isset($errors['cost'])): ?>form__item--invalid<?php endif; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="<?php
                if (isset($adv['min_next_bet'])) {
                    echo format_sum($adv['min_next_bet']);
                }
                ?>">
                <span class="form__error"><?= isset($errors['cost']) ? esc($errors['cost']) : null; ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
            <table class="history__list">
                <?php foreach ($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name">
                            <?= isset($bet['user_name']) ? esc($bet['user_name']) : null; ?>
                        </td>
                        <td class="history__price"><?php
                            if (isset($bet['price'])) {
                                echo format_sum((float)$bet['price']);
                            } ?></td>
                        <td class="history__time">
                            <?php
                            if (isset($bet['date'])) {
                                echo date('y.m.d', strtotime($bet['date'])) . ' в ' . date('H:i', strtotime($bet['date']));
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
          </div>
        </div>
      </div>
    </section>
