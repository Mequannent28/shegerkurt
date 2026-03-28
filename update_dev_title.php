<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $pdo->exec("ALTER TABLE company_info ADD COLUMN IF NOT EXISTS dev_title VARCHAR(150) AFTER dev_name");
    
    // Set the new user confirmed data
    $pdo->exec("UPDATE company_info SET 
        dev_name='Mequannent Gashaw',
        dev_title='Software Developer and ERP Implementer'
        WHERE id=1");
        
    echo "Developer title column and data applied!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
