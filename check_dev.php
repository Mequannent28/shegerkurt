<?php
require 'db.php';
echo "--- COMPANY INFO ---\n";
$stmt = $pdo->query("SELECT dev_photo, dev_name, dev_phone, dev_telegram, dev_linkedin FROM company_info WHERE id=1");
print_r($stmt->fetch());

echo "\n--- USERS ---\n";
$stmt = $pdo->query("SELECT id, full_name, profile_pic FROM users");
print_r($stmt->fetchAll());
