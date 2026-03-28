<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$cols = $pdo->query("DESCRIBE menu_items")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cols, JSON_PRETTY_PRINT);
?>
