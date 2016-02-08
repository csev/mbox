
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

