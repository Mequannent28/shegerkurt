<?php
require_once 'db.php';
$items = $pdo->query("SELECT name, category FROM menu_items")->fetchAll(PDO::FETCH_ASSOC);
print_r($items);
