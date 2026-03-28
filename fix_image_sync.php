<?php
require 'c:/xampp/htdocs/foodie-master/db.php';
try {
    $pdo->exec("UPDATE company_info SET 
        qr_code_image='uploads/site/cbe_qr.jpg', 
        dev_photo='uploads/admin/dev_mequannent.jpg' 
        WHERE id=1");
    echo "Files synchronized successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
