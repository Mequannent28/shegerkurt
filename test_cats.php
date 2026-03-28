<?php
require_once 'db.php';
$cats = $pdo->query("SELECT DISTINCT category FROM menu_items")->fetchAll(PDO::FETCH_COLUMN);
print_r($cats);
