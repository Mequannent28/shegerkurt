<?php
session_start();
$_SESSION['admin_id'] = 1;
$_SESSION['admin_name'] = 'System Test';
require_once 'c:/xampp/htdocs/foodie-master/db.php';

// Prepare POST data
$_POST['send_chat_reply'] = '1';
$_POST['session_id'] = '553ab26b4b9a78ab';
$_POST['reply'] = 'FINAL TEST MESSAGE AT ' . date('H:i:s');
$_POST['ajax'] = '1';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest'; // Set this to trigger the JSON return and EXIT

echo "Starting test...\n";
ob_start();
include 'c:/xampp/htdocs/foodie-master/admin_tabs/handlers.php';
$output = ob_get_clean();

echo "Output from handler:\n$output\n";
echo "Done.\n";
?>
