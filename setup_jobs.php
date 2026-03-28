<?php
require 'db.php';
try {
    $pdo->exec("DROP TABLE IF EXISTS job_applications");
    $sql = "CREATE TABLE job_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        applicant_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        gpa DECIMAL(5,2) DEFAULT 0,
        exam_score DECIMAL(5,2) DEFAULT 0,
        photo_url VARCHAR(255) NULL,
        resume_url VARCHAR(255) NULL,
        status ENUM('Pending', 'Reviewed', 'Accepted', 'Rejected', 'Interview', 'Hired') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Recreated job_applications table.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
