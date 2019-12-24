<?= include_template('nav.php', ['categories' => $categories]) ?>

<?php $form_error = count($errors) ? "form--invalid" : ""; ?>

<form class="form container <?= esc($form_error); ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?php if (isset($errors['email'])) :
        ?>form__item--invalid<?php
                           endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= get_post_val('email') ?>">
        <span class="form__error">
            <?= (isset($errors['email']) ? esc($errors['email']) : null); ?>
        </span>
    </div>
    <div class="form__item form__item--last <?php if (isset($errors['password'])) :
        ?>form__item--invalid<?php
                                            endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error">
            <?= (isset($errors['password']) ? esc($errors['password']) : null); ?>
        </span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
