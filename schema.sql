-- удаление базы, если она уже существует
DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8 -- набор используемых символов.
DEFAULT COLLATE utf8_general_ci; -- набор правил для сравнения символов в наборе.

USE yeticave;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    name VARCHAR(128) UNIQUE,
    symbol_code CHAR(128)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    register_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    name VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL UNIQUE,
    contact VARCHAR(128)
);

CREATE TABLE lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    name VARCHAR(128) NOT NULL,
    description TEXT NOT NULL,
    img TEXT NOT NULL,
    start_price INT NOT NULL,
    end_date DATETIME NOT NULL,
    step INT NOT NULL,
    author_id INT NOT NULL,
    winner_id INT,
    category_id INT NOT NULL,
    FULLTEXT INDEX `lots_search` (`name`, `description`)
);

CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    price INT NOT NULL,
    user_id INT NOT NULL,
	lot_id INT,
	sum INT
);

-- внешние ключи для лотов
ALTER TABLE lots ADD FOREIGN KEY(author_id) REFERENCES users(id);
ALTER TABLE lots ADD FOREIGN KEY(winner_id) REFERENCES users(id);
ALTER TABLE lots ADD FOREIGN KEY(category_id) REFERENCES categories(id);

-- внешние ключи для ставок
ALTER TABLE bets ADD FOREIGN KEY(user_id) REFERENCES users(id);
ALTER TABLE bets ADD FOREIGN KEY(lot_id) REFERENCES lots(id);
