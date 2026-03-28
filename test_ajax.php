<?php
// Simulate the AJAX Request from admin
$url = 'http://localhost/foodie-master/admin.php?tab=chatbot';
$data = [
    'send_chat_reply' => '1',
    'session_id' => '553ab26b4b9a78ab',
    'reply' => 'Simulated AJAX reply',
    'ajax' => '1'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);
// Need to handle session/login for real test? 
// Actually, admin.php checks $_SESSION['admin_id']. 
// My script won't have it.
// So I should disable the check for testing or bypass it.
?>
