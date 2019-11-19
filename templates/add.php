<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="<?= $category['symbol_code']; ?>.html"><?= $category['name']; ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<form class="form form--add-lot container form--invalid" action="add.php" method="post"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item form__item--invalid"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= get_post_val('lot-name') ?>">
            <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <?php foreach ($categories as $category): ?>
                    <option class="nav__item" value="<?= $category['id']; ?>">
                        <?= $category['name']; ?>
                    </option>
                <?php endforeach ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <div class="form__item form__item--wide">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= get_post_val('message') ?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>
    <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-image" value="">
            <label for="lot-img">
                Добавить
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small">
            <?php $classname = isset($errors['lot_rate']) ?>
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= get_post_val('lot_rate') ?>">
            <span class="form__error">Введите начальную цену</span>
        </div>
        <div class="form__item form__item--small">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= get_post_val('lot-step') ?>">
            <span class="form__error">Введите шаг ставки</span>
        </div>
        <div class="form__item">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_val('lot-date') ?>">
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach ($errors as $val): ?>
                <li><?= $val; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
