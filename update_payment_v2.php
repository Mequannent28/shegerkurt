<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS telebirr_name VARCHAR(100) AFTER qr_code_image");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS telebirr_phone VARCHAR(100) AFTER telebirr_name");
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS telebirr_qr VARCHAR(255) AFTER telebirr_phone");
    
    // Set the new confirmed CBE data
    $pdo->exec("UPDATE company_info SET 
        bank_name='Commercial Bank of Ethiopia (CBE)', 
        account_name='MEKUANINT GASHAW ASNAKE', 
        account_number='1000580733356',
        qr_code_image='uploads/site/cbe_qr.jpg',
        telebirr_name='MEKUANINT GASHAW ASNAKE',
        telebirr_phone='0920123456',
        telebirr_qr='uploads/site/telebirr_qr.jpg'
        WHERE id=1");
        
    echo "Payment columns and updated data applied!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
