<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$latest = $pdo->query("SELECT * FROM chat_messages ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
echo "LATEST ID: " . $latest['id'] . "\n";
echo "MESSAGE: " . $latest['message'] . "\n";
?>
