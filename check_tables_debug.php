<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';
$tables = $pdo->query("SELECT id, table_name, image_url FROM restaurant_tables")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($tables, JSON_PRETTY_PRINT);
?>
