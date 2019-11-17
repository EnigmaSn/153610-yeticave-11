<?php
$lots_sql = "
    SELECT lots.id  AS lot_id, lots.name AS lot_name, create_date,
    end_date, start_price, img, start_price + step AS current_price, 
    categories.name AS category_name
    FROM lots 
    JOIN categories ON lots.category_id = categories.id 
    ORDER BY lots.create_date DESC;";
$categories_sql = "
    SELECT id, name, symbol_code FROM categories;";
$lot_by_id_sql = "
    SELECT lots.id, create_date, lots.name AS lot_name, description, img, start_price + step AS current_price,
    end_date, step, author_id, winner_id, categories.name AS category_id
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE lots.id = ?;";
