<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$sid = '553ab26b4b9a78ab'; // From debug output
$reply = 'Test reply from system';
$stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, sender, message, is_read) VALUES (?, 'Admin', ?, 1)");
$stmt->execute([$sid, $reply]);
echo "Insert test successful!";
?>
