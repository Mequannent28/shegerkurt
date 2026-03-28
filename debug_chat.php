<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$msgs = $pdo->query("SELECT * FROM chat_messages ORDER BY id DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($msgs, JSON_PRETTY_PRINT);
?>
