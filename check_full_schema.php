<?php
require 'db.php';
$stmt = $pdo->query("DESCRIBE company_info");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Columns in company_info:\n";
foreach($results as $row) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
