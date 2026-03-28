<?php
session_start();
require_once 'db.php';
echo "<pre style='font-family:monospace; background:#1e293b; color:#f1f5f9; padding:20px; border-radius:10px;'>";

// 1. Check upload dir
$upload_dir = __DIR__ . '/uploads/admin/';
echo "=== UPLOAD DIR ===\n";
echo "Path: $upload_dir\n";
echo "Exists: " . (is_dir($upload_dir) ? "✅ YES" : "❌ NO") . "\n";
echo "Writable: " . (is_writable($upload_dir) ? "✅ YES" : "❌ NO") . "\n\n";

// 2. Check DB
echo "=== DATABASE ===\n";
$users = $pdo->query("SELECT id, full_name, profile_pic FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $u) {
    echo "ID={$u['id']} | Name={$u['full_name']} | Pic=" . ($u['profile_pic'] ?: 'EMPTY') . "\n";
    if ($u['profile_pic']) {
        $full = __DIR__ . '/' . $u['profile_pic'];
        echo "  File exists on disk: " . (file_exists($full) ? "✅ YES" : "❌ NO - $full") . "\n";
    }
}

// 3. Session data
echo "\n=== SESSION ===\n";
echo "admin_pic: " . ($_SESSION['admin_pic'] ?? 'NOT SET') . "\n";

echo "\n=== PHP UPLOAD CONFIG ===\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "file_uploads: " . ini_get('file_uploads') . "\n";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "\n";

echo "</pre>";

// 4. Show a working test upload form
echo '<h2>Test Direct Upload</h2>';
echo '<form method="POST" enctype="multipart/form-data">';
echo '<input type="file" name="test_pic" accept="image/*"><br><br>';
echo '<button type="submit" name="do_test">Upload Test</button>';
echo '</form>';

if (isset($_POST['do_test']) && isset($_FILES['test_pic']) && $_FILES['test_pic']['error'] == 0) {
    echo "<pre style='background:#052e16; color:#bbf7d0; padding:15px; border-radius:8px;'>";
    echo "File name: " . $_FILES['test_pic']['name'] . "\n";
    echo "File size: " . $_FILES['test_pic']['size'] . " bytes\n";
    echo "Tmp name: " . $_FILES['test_pic']['tmp_name'] . "\n";
    echo "Tmp exists: " . (file_exists($_FILES['test_pic']['tmp_name']) ? "YES" : "NO") . "\n";
    
    $ext = strtolower(pathinfo($_FILES['test_pic']['name'], PATHINFO_EXTENSION));
    $dest = __DIR__ . '/uploads/admin/test_' . time() . '.' . $ext;
    $moved = move_uploaded_file($_FILES['test_pic']['tmp_name'], $dest);
    echo "Move result: " . ($moved ? "✅ SUCCESS → $dest" : "❌ FAILED") . "\n";
    if ($moved) {
        echo "\n<img src='uploads/admin/" . basename($dest) . "' style='width:100px; border-radius:50%;'>\n";
        // Now update DB
        $pdo->prepare("UPDATE users SET profile_pic=? WHERE id=?")
            ->execute(["uploads/admin/" . basename($dest), $_SESSION['admin_id'] ?? 1]);
        $_SESSION['admin_pic'] = "uploads/admin/" . basename($dest);
        echo "✅ DB and Session updated!\n";
        echo "Refresh admin panel to see your photo.\n";
    }
    echo "</pre>";
}
?>
<br><a href="admin.php" style="color:blue">→ Back to Admin Panel</a>
