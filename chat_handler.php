<?php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Send message
    $sid = $_POST['session_id'] ?? '';
    $msg = $_POST['message'] ?? '';
    $name = $_POST['customer_name'] ?? '';
    $phone = $_POST['customer_phone'] ?? '';
    $dept = $_POST['department'] ?? 'Restaurant';

    if (!$sid) $sid = bin2hex(random_bytes(8));

    try {
        // Upsert session
        $check = $pdo->prepare("SELECT COUNT(*) FROM chat_sessions WHERE session_id = ?");
        $check->execute([$sid]);
        if ($check->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO chat_sessions (session_id, customer_name, customer_phone, department) VALUES (?, ?, ?, ?)")
                ->execute([$sid, $name, $phone, $dept]);
        }

        // Insert message
        $pdo->prepare("INSERT INTO chat_messages (session_id, sender, message, is_read) VALUES (?, 'User', ?, 0)")
            ->execute([$sid, $msg]);

        // Handle auto-reply if present
        if (isset($_POST['auto_reply'])) {
            $pdo->prepare("INSERT INTO chat_messages (session_id, sender, message, is_read) VALUES (?, 'Admin', ?, 1)")
                ->execute([$sid, $_POST['auto_reply']]);
        }

        echo json_encode(['success' => true, 'session_id' => $sid]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Fetch messages
    $sid = $_GET['session_id'] ?? '';
    $last_id = (int)($_GET['last_id'] ?? 0);

    if ($sid) {
        try {
            // Mark admin messages as read when fetched by user
            $pdo->prepare("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender = 'Admin'")->execute([$sid]);

            $stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE session_id = ? AND id > ? ORDER BY id ASC");
            $stmt->execute([$sid, $last_id]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'messages' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No session ID']);
    }
}
?>
