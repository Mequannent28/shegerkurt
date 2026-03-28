<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$cols = $pdo->query("SHOW COLUMNS FROM restaurant_tables")->fetchAll();
echo "<pre>";
print_r($cols);
echo "</pre>";
?>
