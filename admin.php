<?php
session_start();
require_once 'db.php';

// Simple login check - ideally you'd have a real login page
// For now, if not logged in, we set a dummy session for demonstration
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the website is set up
try {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'reservations'");
    if ($tableCheck->rowCount() == 0) {
        header('Location: setup_database.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: setup_database.php');
    exit;
}

$active_tab = $_GET['tab'] ?? 'dashboard';

// 1. Fetch Latest Permissions (Keep session in sync)
$current_perms_stmt = $pdo->prepare("SELECT permissions FROM users WHERE id = ?");
$current_perms_stmt->execute([$_SESSION['admin_id']]);
$_SESSION['admin_perms'] = json_decode($current_perms_stmt->fetchColumn() ?: '[]', true);

// 2. Permission Check Helper
function hasPerm($tab) {
    if ($_SESSION['admin_id'] == 1) return true; // Super Admin always full access
    if ($tab === 'dashboard' || $tab === 'profile') return true; // Everyone sees dashboard/profile
    return in_array($tab, $_SESSION['admin_perms'] ?? []);
}

// 3. Block Unauthorized Access
if (!hasPerm($active_tab)) {
    header("Location: admin.php?tab=dashboard");
    exit;
}

$company_info = $pdo->query("SELECT * FROM company_info WHERE id=1")->fetch();

// Include handlers for any POST actions
require_once 'admin_tabs/handlers.php';

// Prepare data for dashboard charts (referenced in scripts.php)
if ($active_tab == 'dashboard') {
    // These counts are used in stat cards
    try {
        $pending_res_count = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status='Pending'")->fetchColumn() ?: 0;
        $order_count = $pdo->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn() ?: 0;
        $menu_count = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn() ?: 0;
        $emp_present_today = $pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date = CURDATE() AND status='Present'")->fetchColumn() ?: 0;
        $pending_salary_count = $pdo->query("SELECT COUNT(*) FROM payroll WHERE status='Pending'")->fetchColumn() ?: 0;
        $fav_count = $pdo->query("SELECT COUNT(*) FROM favorites")->fetchColumn() ?: 0;
        $recent_favorites = $pdo->query("SELECT * FROM favorites ORDER BY created_at DESC LIMIT 5")->fetchAll();
    } catch (Exception $e) {
        $pending_res_count = 0;
        $order_count = 0;
        $menu_count = 0;
        $emp_present_today = 0;
        $pending_salary_count = 0;
        $fav_count = 0;
        $recent_favorites = [];
    }

    // Chart Data
    $chart_labels = json_encode(['Main Course', 'Starter', 'Dessert', 'Beverages']);
    $chart_data = json_encode([45, 25, 15, 15]);
    $perf_labels_json = json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
    $perf_data_json = json_encode([65, 78, 90, 85, 95, 100, 80]);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheger Kurt - Admin Panel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?php require_once 'admin_tabs/styles.php'; ?>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin.php" class="brand" style="padding-bottom: 5px; margin-bottom: 15px;">
            <div class="brand-icon"><i class="fa-solid fa-utensils"></i></div>
            <div>Sheger Kurt</div>
        </a>

        <!-- Sidebar User Profile (Clickable Image Upload) -->
        <div style="background: rgba(255,255,255,0.05); padding: 15px; margin: 0 15px 15px 15px; border-radius: 15px; display: flex; align-items: center; gap: 15px; border: 1px solid rgba(255,255,255,0.05);">
            
            <form id="sidebarPicForm" method="POST" action="admin.php?tab=<?= $active_tab ?>" enctype="multipart/form-data" style="flex-shrink:0;">
                <input type="hidden" name="update_profile" value="1">
                <input type="hidden" name="full_name" value="<?= htmlspecialchars($_SESSION['admin_name']) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['admin_email'] ?? '') ?>">
                <input type="file" name="profile_pic" id="sidebarPicInput" accept="image/*" style="display:none;" onchange="document.getElementById('sidebarPicForm').submit()">
                
                <div onclick="document.getElementById('sidebarPicInput').click()" 
                     style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 2px solid rgba(255,157,45,0.8); cursor: pointer; position: relative;"
                     title="Click to change photo"
                     onmouseover="document.getElementById('sidebarCamOverlay').style.opacity='1'"
                     onmouseout="document.getElementById('sidebarCamOverlay').style.opacity='0'">
                    <img id="sidebarAvatar" src="<?= !empty($_SESSION['admin_pic']) ? htmlspecialchars($_SESSION['admin_pic']) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['admin_name']) . '&background=ff9d2d&color=fff' ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <div id="sidebarCamOverlay" style="position:absolute; inset:0; background:rgba(0,0,0,0.55); display:flex; align-items:center; justify-content:center; opacity:0; transition:0.2s;">
                        <i class="fa-solid fa-camera" style="color:#fff; font-size:16px;"></i>
                    </div>
                </div>
            </form>

            <div style="overflow: hidden;">
                <h4 style="color: #fff; font-size: 14px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 700;"><?= htmlspecialchars($_SESSION['admin_name']) ?></h4>
                <p style="color: rgba(255,255,255,0.6); font-size: 11px; margin: 3px 0 0 0; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;"><?= htmlspecialchars($_SESSION['role'] ?? 'Admin') ?></p>
                <a href="?tab=profile" style="color: rgba(255,157,45,0.8); font-size: 10px; font-weight: 600; text-decoration: none;">Edit Profile</a>
            </div>
        </div>

        <div class="nav-items">
            <a href="?tab=dashboard" class="nav-item <?= $active_tab == 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>

            <?php if(hasPerm('menu')): ?>
            <a href="?tab=menu" class="nav-item <?= $active_tab == 'menu' ? 'active' : '' ?>">
                <i class="fa-solid fa-utensils"></i> Menu Mgmt
            </a>
            <?php endif; ?>

            <?php if(hasPerm('reservations')): ?>
            <a href="?tab=reservations" class="nav-item <?= $active_tab == 'reservations' ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-check"></i> Reservations <span class="nav-badge"><?= $pending_res_count ?? 0 ?></span>
            </a>
            <?php endif; ?>

            <?php if(hasPerm('gallery')): ?>
            <a href="?tab=gallery" class="nav-item <?= $active_tab == 'gallery' ? 'active' : '' ?>">
                <i class="fa-solid fa-images"></i> Gallery
            </a>
            <?php endif; ?>

            <?php if(hasPerm('tables')): ?>
            <a href="?tab=tables" class="nav-item <?= $active_tab == 'tables' ? 'active' : '' ?>">
                <i class="fa-solid fa-table"></i> Tables Mgmt
            </a>
            <?php endif; ?>

            <?php if(hasPerm('staff')): ?>
            <a href="?tab=staff" class="nav-item <?= $active_tab == 'staff' ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i> Staff Mgmt
            </a>
            <?php endif; ?>
            
            <?php if(hasPerm('jobs')): ?>
            <a href="?tab=jobs" class="nav-item <?= $active_tab == 'jobs' ? 'active' : '' ?>">
                <i class="fa-solid fa-briefcase"></i> Job Postings
            </a>
            <?php endif; ?>
            
            <?php if(hasPerm('applications')): ?>
            <a href="?tab=applications" class="nav-item <?= $active_tab == 'applications' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-signature"></i> Job Applications
            </a>
            <?php endif; ?>
            
            <?php if(hasPerm('company')): ?>
            <a href="?tab=company" class="nav-item <?= $active_tab == 'company' ? 'active' : '' ?>">
                <i class="fa-solid fa-pen-to-square"></i> Website Content
            </a>
            <?php endif; ?>

            <?php if(hasPerm('promos')): ?>
            <a href="?tab=promos" class="nav-item <?= $active_tab == 'promos' ? 'active' : '' ?>">
                <i class="fa-solid fa-gift"></i> Promo Mgmt
            </a>
            <?php endif; ?>

            <?php if(hasPerm('blogs')): ?>
            <a href="?tab=blogs" class="nav-item <?= $active_tab == 'blogs' ? 'active' : '' ?>">
                <i class="fa-solid fa-newspaper"></i> Blog Mgmt
            </a>
            <?php endif; ?>

            <?php if(hasPerm('users')): ?>
            <a href="?tab=users" class="nav-item <?= $active_tab == 'users' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-shield"></i> User Control
            </a>
            <?php endif; ?>

            <?php if(hasPerm('chatbot')): ?>
            <a href="?tab=chatbot" class="nav-item <?= $active_tab == 'chatbot' ? 'active' : '' ?>">
                <i class="fa-solid fa-comments"></i> Real-time Chat
            </a>
            <?php endif; ?>

            <?php if(hasPerm('recycle_bin')): ?>
            <a href="?tab=recycle_bin" class="nav-item <?= $active_tab == 'recycle_bin' ? 'active' : '' ?>">
                <i class="fa-solid fa-trash-arrow-up"></i> Recycle Bin
            </a>
            <?php endif; ?>
        </div>

        <div style="padding: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
            <a href="index.php" class="nav-item" style="background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.85); margin-bottom: 8px;">
                <i class="fa-solid fa-eye"></i> View Website
            </a>
            <a href="logout.php" class="nav-item" style="background: rgba(239,68,68,0.15); color: #ef4444;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div style="display: flex; align-items: center; gap: 15px;">
                <button id="sidebarToggle" class="no-print" style="background: var(--primary); color: white; width: 40px; height: 40px; border-radius: 10px; display: none; align-items: center; justify-content: center; font-size: 20px; border: none; cursor: pointer;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <h1 style="margin: 0; font-size: clamp(20px, 5vw, 32px);"><?= ucfirst(str_replace('_', ' ', $active_tab)) ?></h1>
                    <div id="liveClock" class="no-print" style="font-weight: 700; color: #64748b; background: #f1f5f9; padding: 8px 15px; border-radius: 10px; font-size: 14px;">
                        <i class="fa-regular fa-clock"></i> 00:00:00
                    </div>
                </div>
            </div>

            <div class="profile-container">
                <div class="profile-clickarea" onclick="toggleLogout()">
                    <div style="text-align: right;">
                        <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                        <div style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;"><?= htmlspecialchars($_SESSION['role'] ?? 'Admin') ?></div>
                    </div>
                    <img src="<?= !empty($_SESSION['admin_pic']) ? htmlspecialchars($_SESSION['admin_pic']) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['admin_name']) . '&background=ff2d55&color=fff' ?>" 
                         style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </div>
                <div id="logoutDropdown" class="logout-dropdown">
                    <a href="?tab=profile" class="nav-item"><i class="fa-solid fa-user-gear"></i> Profile Settings</a>
                    <a href="logout.php" class="nav-item" style="color: #ef4444 !important;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
            </div>
        </header>

        <!-- Message Alerts -->
        <?php if (isset($msg)): ?>
            <div style="background: #dcfce7; color: #15803d; padding: 15px 25px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #bbf7d0; font-weight: 600; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-check"></i> <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- Tab Content -->
        <?php
        $tab_path = "admin_tabs/$active_tab.php";
        if (file_exists($tab_path)) {
            require_once $tab_path;
        } else {
            echo "<div class='card'><h2>Tab not found</h2><p>The requested management tab '$active_tab' could not be located.</p></div>";
        }
        ?>
        <!-- Developer Attribution -->
        <div style="margin-top: 50px; padding: 30px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; border-radius: 0 0 20px 20px;">
           <div style="font-size: 13px; color: #64748b; font-weight: 600;">System Status: <span style="color: #22c55e;">● Online</span></div>
        </div>
        <style>
            @keyframes slideMover {
                0% { right: -350px; }
                100% { right: 100%; }
            }
            @keyframes pulseOnline {
                0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
                70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
                100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
            }
            .movable-dev-card {
                animation: slideMover 15s linear infinite;
                white-space: nowrap;
            }
        </style>

        <div style="position: fixed; bottom: 110px; width: 100%; pointer-events: none; z-index: 10001;">
           <div class="developer-card movable-dev-card" style="position: absolute; display: inline-flex; align-items: center; gap: 15px; padding: 12px 25px; background: rgba(255,255,255,0.98); backdrop-filter: blur(15px); border-radius: 60px; border: 1.5px solid #ff9d2d44; box-shadow: 0 15px 35px rgba(0,0,0,0.15); transition: 0.3s; cursor: pointer; pointer-events: auto;" 
                onmouseover="this.style.animationPlayState='paused'; this.style.borderColor='var(--primary)'; this.style.transform='scale(1.08)';" 
                onmouseout="this.style.animationPlayState='running'; this.style.borderColor='#ff9d2d44'; this.style.transform='scale(1)';"
                onclick="window.open('https://t.me/<?= htmlspecialchars($company_info['dev_telegram'] ?? 'mequannent_gashaw') ?>', '_blank')">
              <div style="position: relative;">
                <img src="<?= !empty($company_info['dev_photo']) ? htmlspecialchars($company_info['dev_photo']) : 'uploads/admin/dev_mequannent.jpg' ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2.5px solid var(--primary); background: #f8fafc;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
                <div style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: #22c55e; border-radius: 50%; border: 2px solid #fff; animation: pulseOnline 2s infinite;"></div>
              </div>
              <div style="border-right: 1px solid #edf2f7; padding-right: 20px;">
                <div style="font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 900; line-height:1; margin-bottom: 3px;"><?= htmlspecialchars($company_info['dev_title'] ?? 'Software Developer and ERP Implementer') ?></div>
                <div style="font-size: 16px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;"><?= htmlspecialchars($company_info['dev_name'] ?? 'Mequannent Gashaw') ?></div>
              </div>
              <div style="display: flex; gap: 15px; align-items: center;">
                <a href="https://wa.me/<?= preg_replace('/\D/','',$company_info['dev_phone'] ?? '251920000000') ?>" target="_blank" style="color: #25d366; font-size: 20px; transition: 0.3s;" onmouseover="this.style.transform='scale(1.3)'" onmouseout="this.style.transform='scale(1)'"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://t.me/<?= htmlspecialchars($company_info['dev_telegram'] ?? 'mequannent_gashaw') ?>" target="_blank" style="color: #0088cc; font-size: 20px; transition: 0.3s;" onmouseover="this.style.transform='scale(1.3)'" onmouseout="this.style.transform='scale(1)'"><i class="fa-brands fa-telegram"></i></a>
              </div>
           </div>
        </div>
    </div>

    <?php require_once 'admin_tabs/scripts.php'; ?>
</body>
</html>
