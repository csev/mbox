
Cache for the gmane service
---------------------------

This is a copy the content of the Sakai developers mailing list.

You can play with an implementation of this at URLs like

http://mbox.dr-chuck.net/sakai.devel/12/13

Where 12 and 13 are a range of message numbers.  

Configuration
-------------

Copy the *config-dist.php* to *config.php* and edit to set up
the database table and various settings:

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
        'sakai.devel'
    );

Data Loading
------------

Copy the content.sqlite file from somewhere - it is about this size:

    -rw----r-- 1 root root 887058432 May 15 10:56 content.sqlite

Once config.php is OK, run `gsql.php` from the command line:

    php gsql.php
	
You many need to install SQLite 3 for PHP - This worked for me for PHP 7.1:

    apt-get install php7.1-sqlite3

This will fill your database with messages with output that looks as follows:

    php gsql.php 
    SQLite connected
    Query ready
    Skipped null record at 1
    2 2005-12-08 23:34:30
    3 2005-12-09 00:58:01
    4 2005-12-09 09:01:49
    ...
    60420 2015-06-01 21:00:33
    60421 2015-06-01 22:09:17
    Count=60421 Headers=167907270 Bodies=667841116

