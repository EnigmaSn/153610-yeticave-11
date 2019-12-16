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
    <h2><?= $error; ?></h2>
<!--    <p>Данной страницы не существует на сайте.</p>-->
</section>
