<?php
require 'db.php';
$cols = $pdo->query("DESCRIBE company_info")->fetchAll(PDO::FETCH_COLUMN);
echo "Existing Columns:\n";
foreach($cols as $c) echo $c . "\n";
