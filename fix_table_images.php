<?php
require_once 'c:/xampp/htdocs/foodie-master/db.php';

try {
    $pdo->exec("UPDATE restaurant_tables SET image_url = './assets/images/table_1_mesob.png' WHERE id = 1");
    $pdo->exec("UPDATE restaurant_tables SET image_url = './assets/images/banner-1.jpg' WHERE id = 2");
    $pdo->exec("UPDATE restaurant_tables SET image_url = './assets/images/banner-2.jpg' WHERE id = 3");
    $pdo->exec("UPDATE restaurant_tables SET image_url = './assets/images/banner-3.jpg' WHERE id = 4");
    $pdo->exec("UPDATE restaurant_tables SET image_url = './assets/images/banner-4.jpg' WHERE id = 5");

    echo "Table image paths fixed successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
