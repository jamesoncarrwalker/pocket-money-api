CREATE TABLE transaction IF NOT EXISTS
(
    UUID VARCHAR(40),
    transaction_amount FLOAT,
    transaction_type ENUM('CREDIT', 'SPEND'),
    transaction_description VARCHAR(250),
    transaction_description_id VARCHAR(40),
    transaction_added DATETIME DEFAULT 'CURRENT_TIMESTAMP' NOT NULL,
    transaction_updated DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
    added_by VARCHAR(40)
);