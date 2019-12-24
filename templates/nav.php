<nav class="nav">
    <ul class="nav__list container">
        <?php
        if (is_array($categories)):
            foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="/all-lots.php?catid=<?= isset($category['id']) ? esc($category['id']) : null; ?>">
                        <?= isset($category['name']) ? esc($category['name']) : null; ?></a>
                </li>
            <?php endforeach; else: echo $categories; ?>
        <?php endif; ?>
    </ul>
</nav>
