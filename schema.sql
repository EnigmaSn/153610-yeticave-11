CREATE DATABASE yeticave; -- это в консоль?

CREATE TABLE categories (
    id AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    symbol_code CHAR(128),
);

CREATE TABLE lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name CHAR(128) NOT NULL,
    description CHAR(255) NOT NULL,
    img TEXT,
    start_price INT,
    end_date INT,
    step INT,
    author_id CHAR(128) NOT NULL,
    winner_id CHAR(128) NOT NULL,
    category_id INT NOT NULL,

    FOREIGN KEY (author_id) REFERENCES users (id),
    FOREIGN KEY (winner_id) REFERENCES users (id), -- на какую таблицу ссылается?
    FOREIGN KEY (category_id) REFERENCES categories (id),
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    register_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email CHAR(128) NOT NULL,
    name CHAR(128) NOT NULL,
    password CHAR(128) NOT NULL,
    contact VARCHAR,
    lot_id INT, -- их же может быть много, можно называть во множественном числе?
    bet_id INT,

    FOREIGN KEY (lot_id) REFERENCES lots (id),
    FOREIGN KEY (bet_id) REFERENCES bets (id),
);

CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price INT NOT NULL,
    user_id INT,
    lot_id INT,

    FOREIGN KEY (lot_id) REFERENCES lots (id),
    FOREIGN KEY (user_id) REFERENCES users (id),
);
