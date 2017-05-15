<?php
header('Content-Type: text/plain; charset=utf-8');

if ( ! file_exists("config.php") ) { 
    echo("Please see configuration instructions at\n\n");
    echo("https://github.com/csev/mbox\n\n");
    die("Not configured.");
}

require_once "config.php";
require_once "pdo.php";
require_once "tsugi_util.php";

$deflate = function_exists('gzdeflate') && function_exists('gzinflate');

// Make material static for a week
$seconds_to_cache = 604800;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache, public");

# Header set Access-Control-Allow-Origin "*"
header("Access-Control-Allow-Origin: *");

$local_path = route_get_local_path(__DIR__);
$pos = strpos($local_path,'?');
$query = false;
$vars = array();
if ( $pos > 0 ) {
    $query = substr($local_path,$pos+1);
    parse_str($query,$vars);
    $local_path = substr($local_path,0,$pos);
}
$pieces = explode('/',$local_path);

$n = count($pieces);

$first = false;
if ( $n >= 3 ) {
	$first = $n - 3;

	$list_id = array_search($pieces[$first],$ALLOWED);
	if ( $list_id === false ) {
		$first = false;
	}
}
	
if ( $first === false ) {
?>
This server contains an email list.

This server expects a URL of the form:

<?= $CFG->wwwroot . '/' . $ALLOWED[0] ?>/4/5

And returns messages in an mbox format.   No more than 10 
messages can be requested at one time.

There is a copy of this hosted at:

http://mbox.dr-chuck.net/

That is served through CloudFlare and so it should provide
fast access anywhere in the world.
<?php
    exit();
}

// Off we go...
$start = 0+$pieces[$first+1];
$end = 0+$pieces[$first+2];

if ( $start < 1 || $end < 1 ) {
    die("Message numbers must be numeric and > 0");
} else if ( $end <= $start ) {
    die("End message number must be > starting message number");
} else if ( $end > $start+10 ) {
    die("No more than 10 messages can be requested at the same time");
}

$message = $start;
$debug = array();
$output = "";
while ( $message < $end ) {
    $stmt = $pdo->prepare('SELECT message AS message FROM messages
        WHERE message_id = :mi');
    $stmt->execute(array( ':mi' => $message) );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Inflate if necessary
    if ( $CFG->compress ) {
        $row['message'] = gzinflate($row['message']);
    }

    $output .= $row['message'] . "\n";
    $message ++;

}

// Sweet debug output
$dbg = "";
foreach ( $debug as $line ) {
    if (strlen($dbg) > 0 ) $dbg .= ', ';
    $dbg .= $line;
    header('X-Gmane-Debug: '.$dbg);
}

echo($output);
