<nav class="nav">
      <ul class="nav__list container">
          <?php foreach ($categories as $category): ?>
              <li class="nav__item">
                  <a href="<?= $category['symbol_code']; ?>.html"><?= $category['name']; ?></a>
              </li>
          <?php endforeach ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?= $adv['lot_name']; ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="../img/<?= $adv['img']; ?>" width="730" height="548" alt="Сноуборд">
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
                <span class="lot-item__cost"><?= format_sum($adv['current_price']) ?></span>
              </div>
              <div class="lot-item__min-cost">
Мин. ставка <span><?= format_sum($adv['step']) ?></span>
              </div>
            </div>
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="12 000">
                <span class="form__error">Введите наименование лота</span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span><?= $bets_count; ?></span>)</h3>
            <table class="history__list">
                <?php foreach ($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= $bet['user_name']; ?></td>
                        <td class="history__price"><?= format_sum($bet['current_price']); ?></td>
                        <td class="history__time">
                            <?php
                            /* пока без автоматического рассчета даты
                            '5 ' .
                            get_noun_plural_form(5, 'минуту', 'минуты', 'минут') .
                            ' назад'
                             время ставки */

                            //get_elapsed_time($bet['date']);

                            // TODO написать функцию для вывода значений, если прошло менее часа
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
