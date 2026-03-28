<?php
$menus = $pdo->query("SELECT m.*, (SELECT GROUP_CONCAT(customer_email SEPARATOR ', ') FROM favorites WHERE menu_item_id = m.id) as lover_emails FROM menu_items m ORDER BY likes DESC, m.id DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="font-size: 28px; font-weight: 800; color: #1e293b; margin:0;">Menu Management</h2>
        <p style="color: #64748b; font-size: 14px; margin: 4px 0 0 0;">Manage your restaurant dishes and popular items.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- View Toggle -->
        <div style="background: #f1f5f9; padding: 4px; border-radius: 12px; display: flex; gap: 4px; margin-right: 10px;">
            <button onclick="switchMenuView('grid')" id="btnGridView" class="view-toggle-btn active" style="padding: 8px 16px; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-grid-2"></i> Grid
            </button>
            <button onclick="switchMenuView('list')" id="btnListView" class="view-toggle-btn" style="padding: 8px 16px; border-radius: 8px; border: none; font-size: 13px; font-weight: 700; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-list-check"></i> Excel Mode
            </button>
        </div>

        <button class="btn" onclick="document.getElementById('excelFileInput').click();"
            style="background: #10b981; color: #fff; border-radius: 10px; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 8px; border:none; cursor:pointer;"><i
                class="fa-solid fa-file-excel"></i> Import Excel</button>
        <button class="btn" onclick="document.getElementById('addDishModal').style.display='flex';"
            style="background: #2563eb; color: #fff; border-radius: 10px; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 8px; border:none; cursor:pointer;"><i
                class="fa-solid fa-plus"></i> Add New Item</button>
        <input type="file" id="excelFileInput" accept=".csv" style="display: none;" onchange="handleExcelFile(this)">
    </div>
</div>

<style>
    .view-toggle-btn { background: transparent; color: #64748b; }
    .view-toggle-btn.active { background: #fff; color: #1e293b; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
    .menu-card { background: #fff; border-radius: 20px; overflow: hidden; position: relative; transition: 0.4s; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .menu-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
    
    .card-img-wrapper { height: 200px; width: 100%; position: relative; overflow: hidden; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .menu-card:hover .card-img-wrapper img { transform: scale(1.1); }
    
    .card-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; gap: 10px; opacity: 0; transition: 0.3s; pointer-events: none; }
    .menu-card:hover .card-overlay { opacity: 1; pointer-events: all; }
    
    .overlay-btn { width: 40px; height: 40px; border-radius: 50%; background: #fff; color: #1e293b; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: 0.2s; font-size: 16px; }
    .overlay-btn:hover { transform: scale(1.1); background: var(--blue); color: #fff; }
    
    .card-price-badge { position: absolute; top: 15px; left: 15px; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px); padding: 5px 12px; border-radius: 30px; font-weight: 800; color: #1e293b; font-size: 13px; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .card-cat-badge { position: absolute; top: 15px; right: 15px; background: rgba(37, 99, 235, 0.9); color: #fff; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; z-index: 2; }
    
    .card-body { padding: 20px; }
    .card-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 8px; display: block; }
    .card-desc { font-size: 13px; color: #64748b; line-height: 1.5; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 15px; }
    
    .card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f1f5f9; }
    .uom-label { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
    
    .excel-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .excel-table th { padding: 12px 15px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; }
    .excel-row { background: #fff; transition: 0.3s; }
    .excel-row:hover { background: #fdfdfd; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
    .excel-row td { padding: 15px; border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9; }
    .excel-row td:first-child { border-left: 1px solid #f1f5f9; border-radius: 12px 0 0 12px; }
    .excel-row td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 12px 12px 0; }
</style>

<!-- GRID VIEW -->
<div id="viewGrid" class="menu-grid">
    <?php foreach ($menus as $idx => $m): ?>
    <div class="menu-card" data-id="<?=$m['id']?>">
        <div class="card-price-badge"><?= number_format($m['price'], 2) ?> ETB</div>
        <div class="card-cat-badge"><?= htmlspecialchars($m['category']) ?></div>
        
        <div class="card-img-wrapper">
            <img src="<?= !empty($m['image_url']) ? htmlspecialchars($m['image_url']) : 'https://via.placeholder.com/300' ?>" alt="">
            <div class="card-overlay">
                <button onclick="viewMenu(<?= htmlspecialchars(json_encode($m)) ?>)" class="overlay-btn" title="View"><i class="fa-solid fa-eye"></i></button>
                <button onclick="editMenu(<?= htmlspecialchars(json_encode($m)) ?>)" class="overlay-btn" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button onclick="modernDelete('delete_menu', '<?= $m['id'] ?>', '<?= htmlspecialchars($m['name'], ENT_QUOTES) ?>', 'Menu Item')" class="overlay-btn" style="color:#ef4444;" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>
        
        <div class="card-body">
            <span class="card-title"><?= htmlspecialchars($m['name']) ?></span>
            <p class="card-desc"><?= htmlspecialchars($m['description'] ?: 'No description provided for this item.') ?></p>
            
            <div class="card-footer">
                <div>
                    <span class="uom-label">UOM:</span>
                    <span style="font-weight:700; color:#1e293b; font-size:13px;"><?= htmlspecialchars($m['uom'] ?? 'pcs') ?></span>
                </div>
                <div style="display:flex; align-items:center; gap:5px; color:#ef4444; font-weight:700; font-size:13px;">
                    <i class="fa-solid fa-heart"></i> <?= $m['likes'] ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- LIST VIEW (EXCEL MODE) -->
<div id="viewList" style="display:none;">
    <div style="background: #fff; padding: 10px; border-radius: 15px; border: 1px solid #f0f0f0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div id="menu_bulk_actions" style="display: none; align-items: center; gap: 15px; padding-left: 10px;">
            <span id="menu_selected_count" style="font-size: 14px; font-weight: 700; color: #2563eb;">0 items selected</span>
            <form method="POST" id="menu_bulk_form">
                <input type="hidden" name="menu_bulk_ids" id="menu_bulk_ids_input">
                <button type="submit" name="bulk_delete_menu" class="btn" onclick="return confirm('Delete selected dishes?')" style="background:#fef2f2; color:#ef4444; border:1px solid #fee2e2; padding:6px 15px; border-radius:8px; font-weight:700; font-size:12px; cursor:pointer;">Delete Selected</button>
            </form>
        </div>
        <div style="flex:1;"></div>
    </div>

    <table class="excel-table">
        <thead>
            <tr>
                <th style="width:40px; text-align:center;"><input type="checkbox" id="select_all_menu" onchange="toggleSelectAllMenu(this)" style="width:16px; height:16px; cursor:pointer;"></th>
                <th style="width:60px;">NO.</th>
                <th>ARTICLE NAME</th>
                <th>UOM</th>
                <th>CATEGORY</th>
                <th>PRICE (ETB)</th>
                <th style="text-align:center;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $idx => $m): ?>
            <tr class="excel-row">
                <td style="text-align:center;"><input type="checkbox" class="menu-checkbox" value="<?= $m['id'] ?>" onchange="updateMenuBulkUI()" style="width:16px; height:16px; cursor:pointer;"></td>
                <td style="font-weight:700; color:#64748b;">#<?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?></td>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="<?= !empty($m['image_url']) ? htmlspecialchars($m['image_url']) : 'https://via.placeholder.com/40' ?>" style="width:35px; height:35px; border-radius:8px; object-fit:cover;">
                        <span style="font-weight:700; color:#1e293b;"><?= htmlspecialchars($m['name']) ?></span>
                    </div>
                </td>
                <td><span style="background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; color:#475569;"><?= strtoupper(htmlspecialchars($m['uom'] ?? 'pcs')) ?></span></td>
                <td><span style="color:#64748b; font-size:13px;"><?= htmlspecialchars($m['category']) ?></span></td>
                <td><span style="font-weight:800; color:#2563eb;"><?= number_format($m['price'], 2) ?></span></td>
                <td>
                    <div style="display:flex; justify-content:center; gap:10px;">
                        <button onclick="editMenu(<?= htmlspecialchars(json_encode($m)) ?>)" style="background:none; border:none; color:#2563eb; cursor:pointer; font-size:14px;"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button onclick="modernDelete('delete_menu', '<?= $m['id'] ?>', '<?= htmlspecialchars($m['name'], ENT_QUOTES) ?>', 'Menu Item')" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:14px;"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Scripts for Switching Views -->
<script>
    function switchMenuView(view) {
        const grid = document.getElementById('viewGrid');
        const list = document.getElementById('viewList');
        const btnGrid = document.getElementById('btnGridView');
        const btnList = document.getElementById('btnListView');
        
        if (view === 'grid') {
            grid.style.display = 'grid';
            list.style.display = 'none';
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
            localStorage.setItem('admin_menu_view', 'grid');
        } else {
            grid.style.display = 'none';
            list.style.display = 'block';
            btnGrid.classList.remove('active');
            btnList.classList.add('active');
            localStorage.setItem('admin_menu_view', 'list');
        }
    }
    
    // Auto-restore view
    window.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('admin_menu_view');
        if (saved) switchMenuView(saved);
    });

    function toggleSelectAllMenu(source) {
        document.querySelectorAll('.menu-checkbox').forEach(cb => cb.checked = source.checked);
        updateMenuBulkUI();
    }
    
    function updateMenuBulkUI() {
        const checked = document.querySelectorAll('.menu-checkbox:checked');
        const bulk = document.getElementById('menu_bulk_actions');
        const count = document.getElementById('menu_selected_count');
        const ids = document.getElementById('menu_bulk_ids_input');
        
        if (checked.length > 0) {
            bulk.style.display = 'flex';
            count.innerText = checked.length + ' items selected';
            ids.value = Array.from(checked).map(cb => cb.value).join(',');
        } else {
            bulk.style.display = 'none';
        }
    }

    function previewMenuImage(input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).style.display = 'block';
                if(placeholderId) document.getElementById(placeholderId).style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<!-- Add Dish Modal -->
<div class="modal-overlay" id="addDishModal" style="display: none;">
    <div class="modal-content" style="max-width: 650px;">
        <div class="card-header" style="border-bottom:none; margin-bottom:5px; padding-bottom:0;">
            <span class="card-title" style="font-size: 22px; font-weight:800;">Create New Dish</span>
            <button type="button" onclick="document.getElementById('addDishModal').style.display='none';" class="btn"
                style="background:none; border:none; color:#888; font-size:22px; cursor:pointer; padding:0;"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data" style="padding: 20px;">
            <input type="hidden" name="add_menu" value="1">
            <div style="display: flex; gap: 25px;">
                <div style="flex: 1;">
                    <div style="width: 100%; aspect-ratio: 1; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        <input type="file" name="dish_photo" accept="image/*" style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewMenuImage(this, 'add_menu_preview', 'add_menu_placeholder')">
                        <div id="add_menu_placeholder" style="text-align: center; color: #94a3b8;">
                            <i class="fa-solid fa-camera" style="font-size: 40px; margin-bottom: 10px;"></i><br>
                            <span style="font-size: 11px; font-weight:700;">Drag or Click to Upload</span>
                        </div>
                        <img id="add_menu_preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div style="flex: 1.5;">
                    <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">DISH NAME</label>
                    <input type="text" name="name" placeholder="E.g. Special Beef Kurt" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom:15px; font-weight:600;">
                    
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
                        <div>
                            <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">CATEGORY</label>
                            <input type="text" name="category" placeholder="Main, Drink..." required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">PRICE (ETB)</label>
                            <input type="number" step="0.01" name="price" placeholder="0.00" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                        </div>
                    </div>
                    
                    <div>
                        <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">UOM (UNIT OF MEASURE)</label>
                        <select name="uom" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="portion">Portion</option>
                            <option value="bottle">Bottle</option>
                            <option value="glass">Glass</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div style="margin-top:20px;">
                <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">DESCRIPTION</label>
                <textarea name="description" placeholder="Write a short tempting description of the dish..." rows="3" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; resize:none;"></textarea>
            </div>
            
            <div style="margin-top:15px;">
                <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">IMAGE URL (IF NOT UPLOADING)</label>
                <input type="url" name="image_url" placeholder="https://..." style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; background: #f8fafc; margin-left:-20px; margin-right:-20px; margin-bottom:-20px; padding:20px; border-radius: 0 0 15px 15px;">
                <button type="button" class="btn" onclick="document.getElementById('addDishModal').style.display='none';" style="background: none; color: #64748b; border: none; font-weight: 700; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn" style="background: #2563eb; color: #fff; border-radius: 10px; padding: 12px 30px; font-weight: 700; border:none; cursor:pointer; box-shadow: 0 4px 10px rgba(37,99,235,0.2);">Save New Dish</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Menu Modal -->
<div class="modal-overlay" id="editMenuModal" style="display: none;">
    <div class="modal-content" style="max-width: 650px;">
        <div class="card-header" style="border-bottom:none; margin-bottom:5px; padding-bottom:0;">
            <span class="card-title" style="font-size: 22px; font-weight:800;">Edit Menu Item</span>
            <button onclick="closeModals()" class="btn" style="background:none; border:none; color:#888; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" style="padding: 20px;">
            <input type="hidden" name="update_menu" value="1">
            <input type="hidden" name="id" id="edit_menu_id">

            <div style="display: flex; gap: 25px;">
                <div style="flex: 1;">
                    <div style="width: 100%; aspect-ratio: 1; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        <input type="file" name="dish_photo" accept="image/*" style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewMenuImage(this, 'edit_menu_preview', 'edit_menu_placeholder')">
                        <div id="edit_menu_placeholder" style="text-align: center; color: #94a3b8; display: none;">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 40px; margin-bottom: 10px;"></i><br>
                            <span style="font-size: 11px; font-weight:700;">Change Image</span>
                        </div>
                        <img id="edit_menu_preview" style="display: block; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div style="flex: 1.5;">
                    <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">DISH NAME</label>
                    <input type="text" name="name" id="edit_menu_name" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom:15px; font-weight:600;">
                    
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:15px;">
                        <div>
                            <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">CATEGORY</label>
                            <input type="text" name="category" id="edit_menu_category" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">PRICE (ETB)</label>
                            <input type="number" step="0.01" name="price" id="edit_menu_price" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                        </div>
                    </div>
                    
                    <div>
                        <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">UOM (UNIT OF MEASURE)</label>
                        <select name="uom" id="edit_menu_uom" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="portion">Portion</option>
                            <option value="bottle">Bottle</option>
                            <option value="glass">Glass</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">DESCRIPTION</label>
                <textarea name="description" id="edit_menu_desc" rows="3" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; resize:none;"></textarea>
            </div>
            
            <div style="margin-top:15px;">
                <label style="font-size:12px; font-weight:700; color:#64748b; margin-bottom:5px; display:block;">IMAGE URL</label>
                <input type="url" name="image_url" id="edit_menu_image" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; background: #f8fafc; margin-left:-20px; margin-right:-20px; margin-bottom:-20px; padding:20px; border-radius: 0 0 15px 15px;">
                <button type="button" class="btn" onclick="closeModals()" style="background: none; color: #64748b; border: none; font-weight: 700; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn" style="background: #2563eb; color: #fff; border-radius: 10px; padding: 12px 30px; font-weight: 700; border:none; cursor:pointer;">Update Dish Info</button>
            </div>
        </form>
    </div>
</div>

<!-- View Menu Modal -->
<div class="modal-overlay" id="viewMenuModal" style="display: none;">
    <div class="modal-content" style="max-width: 500px; border-radius: 25px; overflow: hidden;">
        <div id="view_menu_img_container" style="height: 250px; position:relative;">
            <img id="view_menu_img" src="" style="width: 100%; height: 100%; object-fit: cover;">
            <button onclick="closeModals()" style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.8); border:none; width:35px; height:35px; border-radius:50%; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <div style="padding: 30px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px;">
                <div>
                    <h3 id="view_menu_name" style="font-size:24px; font-weight:800; color:#1e293b; margin:0;"></h3>
                    <span id="view_menu_category" style="font-size:12px; color:#2563eb; font-weight:700; text-transform:uppercase;"></span>
                </div>
                <div style="text-align:right;">
                    <div id="view_menu_price" style="font-size:20px; font-weight:800; color:#1e293b;"></div>
                    <span id="view_menu_uom" style="font-size:11px; color:#64748b; font-weight:600;">per portion</span>
                </div>
            </div>
            
            <p id="view_menu_desc" style="font-size:15px; color:#475569; line-height:1.6; margin-bottom:25px;"></p>
            
            <button onclick="closeModals()" class="btn" style="width:100%; background:#1e293b; color:#fff; padding:15px; border-radius:15px; font-weight:700; border:none; cursor:pointer;">Close Window</button>
        </div>
    </div>
</div>

<script>
    function editMenu(m) {
        document.getElementById('edit_menu_id').value = m.id;
        document.getElementById('edit_menu_name').value = m.name;
        document.getElementById('edit_menu_category').value = m.category;
        document.getElementById('edit_menu_price').value = m.price;
        document.getElementById('edit_menu_uom').value = m.uom || 'pcs';
        document.getElementById('edit_menu_desc').value = m.description;
        document.getElementById('edit_menu_image').value = m.image_url;
        
        const preview = document.getElementById('edit_menu_preview');
        const placeholder = document.getElementById('edit_menu_placeholder');
        if (m.image_url) {
            preview.src = m.image_url;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
        document.getElementById('editMenuModal').style.display = 'flex';
    }

    function viewMenu(m) {
        document.getElementById('view_menu_name').innerText = m.name;
        document.getElementById('view_menu_category').innerText = m.category;
        document.getElementById('view_menu_price').innerText = parseFloat(m.price).toFixed(2) + ' ETB';
        document.getElementById('view_menu_uom').innerText = 'per ' + (m.uom || 'pcs');
        document.getElementById('view_menu_desc').innerText = m.description || 'Enjoy our delicious and freshly prepared ' + m.name + '. Made with high quality ingredients and served with love.';
        
        const img = document.getElementById('view_menu_img');
        img.src = m.image_url || 'https://via.placeholder.com/500';
        
        document.getElementById('viewMenuModal').style.display = 'flex';
    }
</script>