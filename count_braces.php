<?php
$content = file_get_contents('c:\xampp\htdocs\foodie-master\admin_tabs\handlers.php');
$lines = explode("\n", $content);
$depth = 0;
$prev_depth = 0;
foreach($lines as $num => $line) {
    $clean = $line;
    // Remove strings to avoid counting braces inside strings
    $clean = preg_replace('/"[^"]*"/', '""', $clean);
    $clean = preg_replace("/'[^']*'/", "''", $clean);
    if (strpos($clean, '//') !== false) {
        $clean = substr($clean, 0, strpos($clean, '//'));
    }
    $prev_depth = $depth;
    for ($i=0; $i<strlen($clean); $i++) {
        if ($clean[$i] === '{') $depth++;
        if ($clean[$i] === '}') $depth--;
    }
    if ($depth !== $prev_depth) {
        echo "Line " . ($num+1) . ": depth $prev_depth -> $depth\n";
    }
}
echo "\nFinal depth: $depth\n";
?>
