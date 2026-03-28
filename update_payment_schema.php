<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS bank_name VARCHAR(100) AFTER dev_linkedin");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS account_name VARCHAR(100) AFTER bank_name");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS account_number VARCHAR(100) AFTER account_name");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS qr_code_image VARCHAR(255) AFTER account_number");
    
    // Set some defaults
    $pdo->exec("UPDATE company_info SET 
        bank_name='CBE (Commercial Bank of Ethiopia)', 
        account_name='Sheger Kurt Restaurant', 
        account_number='1000123456789',
        qr_code_image='https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg' 
        WHERE id=1");
        
    echo "Payment columns added successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
