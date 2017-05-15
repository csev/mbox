<?php
require_once "config.php";
require_once "pdo.php";

if (! php_sapi_name() == "cli" ) die('Command line only');

$db = new SQLite3('.data/content.sqlite');
print "SQLite connected\n";
// var_dump($db);

$results = $db->query('SELECT headers, body, sent_at FROM Messages ORDER BY sent_at');
print "Query ready\n";
// var_dump($results);
$count = 0;
$hlen = 0;
$blen = 0;
$message_id = 0;
while ($row = $results->fetchArray()) {
    $count++;
    if ( is_null($row[0]) || is_null($row[1]) || is_null($row[2]) ) {
        print "Skipped null record at $count\n";
        continue;
    }
    $hlen += strlen($row[0]);
    $blen += strlen($row[1]);
    
    $header = trim($row[0]);
    $body = trim($row[1]);
    $sent_at = strtotime($row[2]);
    $sent_at = substr($row[2],0,19);
    // print "S1 $sent_at\n";
    $sent_at = str_replace("T"," ",$sent_at);
    // print "S2 $sent_at\n";
    $text = $header."\n\n".$body;
    $snippet = trim(substr($header,0,200))."...\n\n".trim(substr($body,0,200))."...\n";
    $sha256 = hash('sha256', $text);

    // Deflate if necessary
    $insert_text = $text;
    if ( $CFG->compress ) {
        $insert_text = gzdeflate($text);
    }
    // print "----\n$sha256\n$snippet\n";
    // var_dump($row);

    $message_id++;
    $stmt = $pdo->prepare('INSERT INTO messages
        (message_id, message_sha256, snippet, message, sent_at, updated_at) VALUES
        (:id, :sha, :snip, :mess, :sent, NOW())
        ON DUPLICATE KEY UPDATE 
	message_sha256=:sha,snippet=:snip, message=:mess, sent_at=:sent, updated_at=NOW()');
    $stmt->execute( array(
        ':id' => $message_id,
        ':sha' => $sha256,
        ':snip' => $snippet,
        ':mess' => $insert_text,
        ':sent' => $sent_at)
    );
    print "$count $sent_at\n";

    // if ( $count > 5 ) break;
}

print "Count=$count Headers=$hlen Bodies=$blen\n";


