<?php
require_once "config.php";
require_once "pdo.php";

$db = new SQLite3('.data/content.sqlite');
print "SQLite connected\n";

$results = $db->query('SELECT headers, body, sent_at FROM Messages ORDER BY sent_at');
print "Query ready\n";
$count = 0;
$hlen = 0;
$blen = 0;
while ($row = $results->fetchArray()) {
    $count++;
    if ( is_null($row[0]) || is_null($row[1]) || is_null($row[2]) ) {
        print $count."\n";
        continue;
    }
    $hlen += strlen($row[0]);
    $blen += strlen($row[1]);
    
    $header = trim($row[0]);
    $body = trim($row[1]);
    $sent_at = $row[3];
    $text = $header."\n\n".$body;
    $snippet = trim(substr($header,0,200))."...\n\n".trim(substr($body,0,200))."...\n";
    $sha256 = hash('sha256', $text);

    // Deflate if necessary
    $insert_text = $text;
    if ( $CFG->compress ) {
        $insert_text = gzdeflate($text);
    }
    print "----\n$sha256\n$snippet\n";
    // var_dump($row);

    $stmt = $pdo->prepare('INSERT INTO messages
        (message_sha256, snippet, message, sent_at, updated_at) VALUES
        (:sha, :snip, :mess, :sent, NOW())
        ON DUPLICATE KEY UPDATE updated_at=NOW()');
    $stmt->execute( array(
        ':sha' => $sha256,
        ':snip' => $snippet,
        ':mess' => $insert_text,
        ':sent' => $send_at)
    );
    print "$count $sent_at\n";

    if ( $count > 2 ) break;
}

print "Count=$count Headers=$hlen Bodies=$blen\n";


