<?php
require 'db.php';
// Get current admin info
$admin = $pdo->query("SELECT profile_pic, full_name FROM users WHERE id=1")->fetch();
if ($admin) {
    $stmt = $pdo->prepare("UPDATE company_info SET dev_photo=?, dev_name=? WHERE id=1");
    $stmt->execute([$admin['profile_pic'], $admin['full_name']]);
    echo "Developer attribution updated to match your profile!\n";
    echo "New Photo: " . $admin['profile_pic'] . "\n";
    echo "New Name: " . $admin['full_name'] . "\n";
} else {
    echo "Admin info not found!\n";
}
