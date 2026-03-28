<?php
require 'db.php';
$stmt = $pdo->query("DESCRIBE company_info");
print_r($stmt->fetchAll());
