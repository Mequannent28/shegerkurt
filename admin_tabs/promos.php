<?php
$promos = $pdo->query("SELECT * FROM promo_items ORDER BY id DESC")->fetchAll();
?>
<div
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 4px;">Promo Slider Management</h2>
        <p style="color: #64748b; font-size: 14px;">Manage the circular food items shown in the homepage slider.</p>
    </div>
    <button onclick="document.getElementById('addPromoModal').style.display='flex';" class="btn"
        style="background: var(--primary); color: #fff; border-radius: 10px; padding: 10px 18px; font-weight: 600;">
        <i class="fa-solid fa-plus"></i> Add New Promo
    </button>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    <?php foreach ($promos as $p): ?>
        <div class="card" style="padding: 20px; text-align: center; border-radius: 20px;">
            <div style="width: 140px; height: 140px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; background: #ff9d2d0f; border: 4px solid #ff9d2d22; padding: 10px;">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;"><?= htmlspecialchars($p['title']) ?></h3>
            <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 20px;"><?= htmlspecialchars($p['description']) ?></p>
            <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                <a href="<?= htmlspecialchars($p['image_url']) ?>" target="_blank" class="btn" style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; text-decoration: none;">
                    <i class="fa-solid fa-eye"></i> View
                </a>
                <button onclick="openEditPromo(<?= htmlspecialchars(json_encode($p)) ?>)" class="btn" style="background: #ecfdf5; color: #059669; border: none; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; cursor: pointer;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                <button onclick="modernDelete('delete_promo', '<?= $p['id'] ?>', '<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>', 'Promo Item')" 
                        style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Add Promo Modal -->
<div class="modal-overlay" id="addPromoModal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="card-header" style="border:none;">
            <span class="card-title">Add New Promo Slider Item</span>
            <button onclick="document.getElementById('addPromoModal').style.display='none';" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_promo" value="1">
            <label>Promo Title</label>
            <input type="text" name="title" placeholder="e.g. Traditional Kurt Dish" required>
            <label style="margin-top:15px; display:block;">Description</label>
            <textarea name="description" rows="3" required></textarea>
            <div style="margin-top:15px;">
                <label>Promo Image (Circular)</label>
                <input type="file" name="promo_photo" required>
            </div>
            <div style="margin-top:25px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="document.getElementById('addPromoModal').style.display='none';" class="btn" style="background:#f1f5f9;">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Promo</button>
            </div>
        </form>
    </div>
</div>
<!-- Edit Promo Modal -->
<div class="modal-overlay" id="editPromoModal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="card-header" style="border:none;">
            <span class="card-title">Edit Promo Item</span>
            <button onclick="document.getElementById('editPromoModal').style.display='none';" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="edit_promo" value="1">
            <input type="hidden" name="promo_id" id="edit_promo_id">
            <label>Promo Title</label>
            <input type="text" name="title" id="edit_promo_title" required>
            <label style="margin-top:15px; display:block;">Description</label>
            <textarea name="description" id="edit_promo_desc" rows="3" required></textarea>
            <div style="margin-top:15px;">
                <label>Change Image (Optional)</label>
                <input type="file" name="promo_photo">
            </div>
            <div style="margin-top:25px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="document.getElementById('editPromoModal').style.display='none';" class="btn" style="background:#f1f5f9;">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Promo</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditPromo(p) {
        document.getElementById('edit_promo_id').value = p.id;
        document.getElementById('edit_promo_title').value = p.title;
        document.getElementById('edit_promo_desc').value = p.description;
        document.getElementById('editPromoModal').style.display = 'flex';
    }
</script>
