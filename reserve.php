<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['full_name'] ?? '';
    $email = $_POST['email_address'] ?? '';
    $persons = $_POST['total_person'] ?? '';
    $date = $_POST['booking_date'] ?? '';
    $time = $_POST['reservation_time'] ?? date('H:i');
    $table_id = $_POST['table_id'] ?? null;
    $message = $_POST['message'] ?? '';
    
    // Extract numeric portion from persons string
    $guests = (int)filter_var($persons, FILTER_SANITIZE_NUMBER_INT) ?: 1;

    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (customer_name, email, reservation_date, reservation_time, guests, table_id, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$name, $email, $date, $time, $guests, $table_id, $message]);
        
        echo "<script>alert('Reservation submitted successfully! We will contact you soon.'); window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    header('Location: index.php');
}
?>
