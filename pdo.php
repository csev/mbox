<?php
require_once "config.php";

$pdo = new PDO($CFG->pdo, $CFG->dbuser, $CFG->dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
CREATE DATABASE gmane DEFAULT CHARACTER SET utf8 ;

GRANT ALL ON gmane.* TO 'fred'@'localhost' IDENTIFIED BY 'zap';
GRANT ALL ON gmane.* TO 'fred'@'127.0.0.1' IDENTIFIED BY 'zap';

CREATE TABLE messages2 (
    message_id       INTEGER NOT NULL AUTO_INCREMENT KEY,
    message_sha256   CHAR(64) NOT NULL,
    snippet          TEXT,
    message          TEXT,
    sent_at          TIMESTAMP NOT NULL DEFAULT 0,
    updated_at       TIMESTAMP NOT NULL DEFAULT 0,

    UNIQUE(message_sha256)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

*/
