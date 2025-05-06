CREATE TABLE conversion_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_currency CHAR(3) NOT NULL,
    to_currency   CHAR(3) NOT NULL,
    input_amount  DECIMAL(15,4) NOT NULL,
    result_amount DECIMAL(15,4) NOT NULL,
    converted_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);