<?php
require_once "config.php";

$pdo = new PDO($CFG->pdo, $CFG->dbuser, $CFG->dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
CREATE DATABASE mbox DEFAULT CHARACTER SET utf8 ;

GRANT ALL ON mbox.* TO 'fred'@'localhost' IDENTIFIED BY 'zap';
GRANT ALL ON mbox.* TO 'fred'@'127.0.0.1' IDENTIFIED BY 'zap';

CREATE TABLE messages (
    message_id       INTEGER NOT NULL KEY,
    message_sha256   CHAR(64) NOT NULL,
    snippet          TEXT,
    message          TEXT,
    sent_at          TIMESTAMP NOT NULL DEFAULT 0,
    updated_at       TIMESTAMP NOT NULL DEFAULT 0
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

*/
