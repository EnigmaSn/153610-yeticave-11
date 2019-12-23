<?php
require_once('db.php');

/**
 * Получение списка новых лотов
 * @param $link - ресурс соединения (уточнить тип данных)
 * @return array - массив списка лотов
 */
function get_lots($link) {
    $sql = "
    SELECT `lots`.`id`  AS `lot_id`, `lots`.`name` AS `lot_name`, `create_date`,
    `end_date`, `start_price`, `img`, `start_price` + `step` AS `current_price`, 
    `categories`.`name` AS `category_name`,
    MAX(bets.sum) as max_bet
    FROM `lots` 
    JOIN `categories` ON `lots`.`category_id` = `categories`.`id` 
    LEFT JOIN bets  ON bets.lot_id = lots.id
    GROUP BY lots.id, lots.create_date
    ORDER BY `lots`.`create_date` DESC;";
    $sql_result = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
    return $lots;
}

/**
 * Получение списка категорий
 * @param $link - ресурс соединения
 * @return array
 */
function get_categories($link) {
    $sql = "
    SELECT `id`, `name`, `symbol_code` FROM `categories`;";
    $sql_result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
    return $categories;
}

/**
 * Получение информации о конкретном лоте по id из параметра запроса
 * @param $link
 * @param $received_lot_id
 * @return array|null
 */
function get_lot_by_id($link, $received_lot_id) {
    $sql = "SELECT `lots`.`id`, `create_date`, `lots`.`name` AS `lot_name`, `description`, `img`, `start_price` AS `current_price`,
    `end_date`, `step`, `author_id`, `winner_id`, `categories`.`name` AS `category_id`,
    MAX(bets.sum) as max_bet
    FROM `lots`
    JOIN `categories` ON `lots`.`category_id` = `categories`.`id`
    LEFT JOIN bets  ON bets.lot_id = lots.id
    WHERE `lots`.`id` = ?
    GROUP BY lots.id;";
    $adv_result = db_get_prepare_stmt($link, $sql, $data = [$received_lot_id]);
    mysqli_stmt_execute($adv_result); // выполняет подготовленный запрос
    $adv_result = mysqli_stmt_get_result($adv_result); // возвращает результат
    $adv = mysqli_fetch_array($adv_result, MYSQLI_ASSOC);

    return $adv ?? [];
}
/**
 * Получение информации о ставках конкретного лота по его id
 * @param $link
 * @param $received_lot_id
 * @return array
 */
function get_bets_for_lot($link, $received_lot_id) {
    $sql = "SELECT `bets`.`id`, `bets`.`date`, `user_id`, `price`, `lot_id`,
        `users`.`name` AS `user_name`, `start_price` + `step` AS `current_price`
        FROM `bets`
        JOIN `users` ON `bets`.`user_id` = `users`.`id`
        JOIN `lots` ON `bets`.`lot_id` = `lots`.`id`
        WHERE `bets`.`lot_id` = ?;";
    $bets_result = db_get_prepare_stmt($link, $sql, $data = [$received_lot_id]);
    mysqli_stmt_execute($bets_result); // выполняет подготовленный запрос
    $bets_result = mysqli_stmt_get_result($bets_result); // возвращает результат
    $bets = mysqli_fetch_all($bets_result, MYSQLI_ASSOC);
    return $bets;
}

function get_password(mysqli $link, string $email) : ?array {
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, $data = [$email]);
    if (!mysqli_stmt_execute($stmt)) {
        exit(mysqli_errno($link));
    }
    $result = mysqli_stmt_get_result($stmt);
    $password = mysqli_fetch_array($result, MYSQLI_ASSOC);

    return $password;
}

function get_user(mysqli $link, string $email) : array
{
    $sql = "SELECT id, name FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        exit(mysqli_error($link));
    }

    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $user;
}

function insert_lot($link, $lot_data) {
    $user_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO `lots` (`name`, `description`, `start_price`, `end_date`, `step`, `category_id`, `img`, `create_date`, `author_id`)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $data = [
        $lot_data['lot-name'],
        $lot_data['message'],
        $lot_data['lot-rate'],
        $lot_data['lot-date'],
        $lot_data['lot-step'],
        $lot_data['category'],
        $lot_data['lot-file'],
        $user_id
    ]);
    $result = mysqli_stmt_execute($stmt); // выполняет запрос, boolean
    if ($result) {
        // возвращает автоматически генерируемый id последнего запроса
        return mysqli_insert_id($link);
    }
    return null;
}

/**
 * Проверка email на наличие в БД
 * @param mysqli $link
 * @param $email
 * @return bool
 */
function check_email(mysqli $link, $email) : bool
{
    $sql = 'SELECT email FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($link, $sql, $data = [$email]);
    if (!mysqli_stmt_execute($stmt)) {
        exit(mysqli_errno($link));
    }
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        return true;
    }

    return false;
}

/**
 * Добаление пользователя в БД
 * @param $link
 * @return bool|null
 */
function insert_user($link) {
    $sql = "INSERT INTO `users` (`register_date`, `email`, `password`, `name`, `contact`)
            VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, $data = [
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'],PASSWORD_DEFAULT),
        'name' => $_POST['name'],
        'contact' => $_POST['message']
    ]);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        return true;
    }
    return null;
}

// число полученных по запросу лотов
function get_lots_count($link, $query) {
    // если есть поисковый GET запрос
    if ($query) {
        $sql = "SELECT COUNT(*) AS count_item
        FROM lots
        WHERE end_date > NOW()
        AND MATCH(name, description) AGAINST(? IN BOOLEAN MODE)";

        $stmt = db_get_prepare_stmt($link, $sql, $data = [$query]);
        if (!mysqli_stmt_execute($stmt)) {
            exit(mysqli_error($link));
        }
        $result = mysqli_stmt_get_result($stmt);
        $lots_found = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($lots_found['count_item'] > 0) {
            return $lots_found['count_item'];
        }

        return "Ничего не найдено по вашему запросу";
    }
    return "Пустой запрос";
}

function get_searching_lots($link, $query, int $limit,
                            int $offset) : array {
    $sql = "SELECT lots.id,
    lots.name,
    start_price,
    img,
    end_date,
    create_date,
    categories.name AS category
    FROM lots
    INNER JOIN categories on lots.category_id = categories.id
    WHERE end_date > NOW()
    AND MATCH(lots.name, description) AGAINST(? IN BOOLEAN MODE)
    ORDER BY create_date DESC
    LIMIT $limit OFFSET $offset";

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$query]);
    if (!mysqli_stmt_execute($stmt)){
        exit(mysqli_error($link));
    }
    $result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
}

function insert_bet(mysqli $link, float $bet, int $lot_id, int $user_id, int $price) : ?bool {
    $sql = "INSERT INTO `bets`
	SET `bets`.`date` = NOW(),
    `bets`.`sum` = ?,
    `lot_id` = ?,
    `user_id` = ?,
    `price` = ?";

    $stmt = db_get_prepare_stmt($link, $sql, $data = [
        $bet,
        $lot_id,
        $user_id,
        $price
    ]);

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return true;
    }
    return null;
}

function get_bets_for_user(mysqli $link, int $user_id) : array {
    $sql = "SELECT l.img,
        l.name,
        b.id as bet_id,
        c.name AS category,
        l.end_date,
        b.lot_id,
        b.price,
        b.date
        FROM bets b
                 INNER JOIN lots l ON b.lot_id = l.id
                 INNER JOIN categories c ON l.category_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.date DESC;";

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit(mysqli_error($link));
    }

    $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($bets as &$bet) {
        $bet['time_back'] = get_bet_timeleft($bet['date']);
    }
    return $bets ?? [];
}

function get_lots_where_winner(mysqli $link, int $user_id) : array {
    $sql = "SELECT id FROM lots WHERE winner_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, $data = [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit(mysqli_error($link));
    }

    $lots_win = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return array_column($lots_win, 'id') ?? [];
}

function get_win_bets_for_user(mysqli $link, array $lots_ids) : array
{
    $sql = "SELECT id FROM bets
        WHERE bets.lot_id=?
        ORDER BY sum DESC LIMIT 1;";
    $bets_win = [];
    foreach ($lots_ids as $id) {
        $stmt = db_get_prepare_stmt($link, $sql, [$id]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            exit(mysqli_error($link));
        }
        $bets_win[] = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    return array_column($bets_win, 'id');
}

function get_lots_without_win(mysqli $link) : array
{
    $sql = "SELECT id FROM lots WHERE end_date <= NOW() AND winner_id IS NULL";
    $lots = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);

    return $lots ?? [];
}

function get_winner(mysqli $link, int $lot_id) : array
{
    $sql = "SELECT b.user_id,
       u.name as user_name,
       u.email,
       l.name as lot_name
    FROM bets b
             INNER JOIN users u ON b.user_id = u.id
             INNER JOIN lots l ON b.lot_id = l.id
    WHERE lot_id = $lot_id
    ORDER BY SUM
        DESC
    LIMIT 1;";
    $result = mysqli_fetch_array(mysqli_query($link, $sql), MYSQLI_ASSOC);

    return $result ?? [];
}

function add_winner_to_lot(mysqli $link, int $lot, int $winner) : bool {
    $sql = "UPDATE lots 
        SET winner_id = $winner 
        WHERE id = $lot;";
    if (!mysqli_query($link, $sql)) {
        exit(mysqli_error($link));
    }

    return true;
}

function get_lots_by_cat_count(mysqli $link, int $category) {
    if ($category !== 0) {
        $sql = "SELECT COUNT(*) as count_item
        FROM lots
        WHERE category_id = ?;";
        $stmt = db_get_prepare_stmt($link, $sql, [$category]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            exit(mysqli_error($link));
        }

        $lots_found = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if ($lots_found['count_item'] > 0) {
            return $lots_found['count_item'];
        }

        return 'Нет лотов по вашему запросу';
    }

    return 'Ошибка';
}

function get_lots_by_category(mysqli $link, int $category, int $limit, int $offset) : array {
    $sql = "
        SELECT l.id,
               l.name,
               l.start_price,
               l.img,
               l.end_date,
               c.name     AS category,
               MAX(b.price) as max_bet
        FROM lots l
                 INNER JOIN categories c ON l.category_id = c.id
                 LEFT JOIN bets b ON l.id = b.lot_id
        WHERE l.category_id = ?
        GROUP BY l.id, l.create_date
        ORDER BY l.create_date DESC
        LIMIT $limit OFFSET $offset;";
    $stmt = db_get_prepare_stmt($link, $sql, $data = [$category]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        exit(mysqli_error($link));
    }

    return $result = mysqli_fetch_all($result, MYSQLI_ASSOC) ?? [];
}

function get_count_bets_for_lot(mysqli $link, int $lot_id): int
{
    $sql = "SELECT COUNT(*) FROM bets WHERE lot_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit(mysqli_error($link));
    }

    $count = mysqli_fetch_array($result, MYSQLI_NUM);

    return $count[0] ?? 0;
}
