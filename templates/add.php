<?= include_template('nav.php', ['categories' => $categories]) ?>

<?php $form_error = count($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?= esc($form_error); ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php if(isset($errors['lot-name'])): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= get_post_val('lot-name') ?>">
            <span class="form__error"><?= (isset($errors['lot-name']) ? esc($errors['lot-name']) : null); ?></span>
        </div>
        <div class="form__item <?php if(isset($errors['category'])): ?>form__item--invalid<?php endif; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <?php foreach ($categories as $category): ?>
                    <option class="nav__item" value="<?= esc($category['id']); ?>">
                        <?= esc($category['name']); ?>
                    </option>
                <?php endforeach ?>
            </select>
            <span class="form__error">
                <?= (isset($errors['category']) ? esc($errors['category']) : null); ?>
            </span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php if(isset($errors['message'])): ?>form__item--invalid<?php endif; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= get_post_val('message') ?></textarea>
        <span class="form__error"><?= esc($errors['message']); ?></span>
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
        <div class="form__item form__item--small <?php if(isset($errors['lot-rate'])): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= get_post_val('lot_rate') ?>">
            <span class="form__error"><?= esc($errors['lot-rate']); ?></span>
        </div>
        <div class="form__item form__item--small <?php if(isset($errors['lot-step'])): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= get_post_val('lot-step') ?>">
            <span class="form__error"><?= esc($errors['lot-step']); ?></span>
        </div>
        <div class="form__item <?php if(isset($errors['lot-date'])): ?>form__item--invalid<?php endif; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_val('lot-date') ?>">
            <span class="form__error"><?= esc($errors['lot-date']); ?></span>
        </div>
    </div>
    <?php if (isset($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach ($errors as $val): ?>
                <li><?= esc($val); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <button type="submit" class="button">Добавить лот</button>
</form>
