<?php

$CFG = new stdClass();

$CFG->pdo       = 'mysql:host=127.0.0.1;port=8889;dbname=gmane'; // MAMP
$CFG->dbuser    = 'fred';
$CFG->dbpass    = 'zap';

$CFG->wwwroot   = 'http://localhost:8888/gmane2';
$CFG->compress  = $deflate = function_exists('gzdeflate') && function_exists('gzinflate');
// $CFG->compress  = false;

// Only add these at the end and keep the same order unless
// you completely empty out the messages table.
$ALLOWED = array(
'gmane.comp.cms.sakai.devel'
);

