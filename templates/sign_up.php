<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="<?= $category['symbol_code']; ?>.html"><?= $category['name']; ?></a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
<?php $form_error = count($errors) ? "form--invalid" : ""; ?>
<form class="form container <?= $form_error; ?>" action="sign_up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if(isset($errors['email'])): ?>form__item--invalid<?php endif; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= get_post_val('email') ?>">
        <span class="form__error">Введите e-mail</span>
    </div>
    <div class="form__item <?php if(isset($errors['password'])): ?>form__item--invalid<?php endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= get_post_val('password') ?>">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?php if(isset($errors['name'])): ?>form__item--invalid<?php endif; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= get_post_val('name') ?>">
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item <?php if(isset($errors['message'])): ?>form__item--invalid<?php endif; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= get_post_val('message') ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
