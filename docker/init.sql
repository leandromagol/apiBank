
CREATE DATABASE IF NOT EXISTS bank_database;
USE bank_database;

CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_number INT NOT NULL UNIQUE,
    balance DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_number INT NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    payment_method CHAR(1) NOT NULL,
    date_time DATETIME NOT NULL
);
