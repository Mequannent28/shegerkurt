<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $sql1 = "CREATE TABLE IF NOT EXISTS job_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        question TEXT NOT NULL,
        option_a VARCHAR(255) NOT NULL,
        option_b VARCHAR(255) NOT NULL,
        option_c VARCHAR(255) NOT NULL,
        option_d VARCHAR(255) NOT NULL,
        correct_answer CHAR(1) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql1);
    echo "Created job_questions table.\n";

    $sql2 = "CREATE TABLE IF NOT EXISTS job_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        applicant_name VARCHAR(100) NOT NULL,
        applicant_email VARCHAR(100) NOT NULL,
        applicant_phone VARCHAR(20) NOT NULL,
        resume_url VARCHAR(255) NULL,
        exam_score DECIMAL(5,2) NULL,
        status ENUM('Pending', 'Interview', 'Hired', 'Rejected') DEFAULT 'Pending',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql2);
    echo "Created job_applications table.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
