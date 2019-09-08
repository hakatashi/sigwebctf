CREATE DATABASE IF NOT EXISTS sqlit;
use sqlit;

CREATE TABLE IF NOT EXISTS users (
	name VARCHAR(255) NOT NULL UNIQUE,
	pass TINYTEXT
);
INSERT IGNORE INTO users (name, pass) VALUES ('admin', 'thisisnotflagblahblahhogefugapiyonyannyan');

CREATE USER hakatashi;
GRANT SELECT ON sqlit.* TO 'hakatashi'@'%';
