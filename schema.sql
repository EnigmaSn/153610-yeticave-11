-- drop database if exist
-- кодировка
CREATE DATABASE yeticave;

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
    category_id INT NOT NULL
);

CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    price INT NOT NULL,
    user_id INT NOT NULL,
	lot_id INT
);

-- прописать связи отдельно
-- связи в php
