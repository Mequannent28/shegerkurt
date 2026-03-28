<?php
$tables = $pdo->query("SELECT * FROM restaurant_tables ORDER BY id ASC")->fetchAll();
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2><i class="fa-solid fa-table"></i> Restaurant Table Management</h2>
        <button class="btn" style="background: var(--primary); padding: 10px 20px;" onclick="document.getElementById('addTableModal').style.display='flex'">
            <i class="fa-solid fa-plus"></i> Add New Table
        </button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <?php foreach ($tables as $t): 
            $is_reserved = $t['status'] == 'Reserved';
            $is_occupied = $t['status'] == 'Occupied';
            $status_color = $is_reserved ? '#ff9d2d' : ($is_occupied ? '#ef4444' : '#22c55e');
            $bg_color = $is_reserved ? '#fff7ed' : ($is_occupied ? '#fef2f2' : '#f0fdf4');
        ?>
        <div class="table-card" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; transition: 0.3s; position: relative;">
            <div style="height: 140px; background: url('<?= htmlspecialchars($t['image_url']) ?>'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; top: 12px; right: 12px; background: <?= $status_color ?>; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                    <?= $t['status'] ?>
                </div>
            </div>
            
            <div style="padding: 20px; text-align: center;">
                <h3 style="margin: 0 0 10px; font-size: 18px; color: #1e293b;"><?= htmlspecialchars($t['table_name']) ?></h3>
                
                <!-- Visual Table Graphic -->
                <div style="display: flex; justify-content: center; align-items: center; margin: 20px 0; position: relative; height: 100px;">
                    <!-- The Table -->
                    <div style="width: 70px; height: 70px; background: <?= $bg_color ?>; border: 3px solid <?= $status_color ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-utensils" style="color: <?= $status_color ?>; font-size: 20px;"></i>
                        <?php if($is_reserved): ?>
                            <div style="position: absolute; top: -15px; background: #ff9d2d; color: white; font-size: 8px; padding: 2px 6px; border-radius: 4px; font-weight: 900;">RESERVED</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- The Chairs (Dynamic based on capacity) -->
                    <?php 
                        $capacity = (int)$t['capacity'];
                        for($i=0; $i<$capacity; $i++): 
                            $angle = (360 / $capacity) * $i;
                            $radius = 45;
                    ?>
                        <div style="position: absolute; width: 14px; height: 14px; background: <?= $status_color ?>; border-radius: 4px; transform: rotate(<?= $angle ?>deg) translate(<?= $radius ?>px) rotate(-<?= $angle ?>deg); opacity: 0.8;"></div>
                    <?php endfor; ?>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 10px 15px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="text-align: left;">
                        <span style="display: block; font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase;">Capacity</span>
                        <span style="font-weight: 700; color: #1e293b;"><?= $capacity ?> Persons</span>
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <button class="btn-icon" style="background: #e0f2fe; color: #0369a1;" onclick='editTable(<?= json_encode($t) ?>)'>
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="" method="POST" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button type="submit" name="delete_table" class="btn-icon" style="background: #fee2e2; color: #ef4444;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.5; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <?= htmlspecialchars($t['description']) ?>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Table Modal -->
<div id="addTableModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h3>Add New Table</h3>
            <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" style="border:none;background:none;cursor:pointer;font-size:20px;">&times;</button>
        </div>
        <form action="admin.php?tab=tables" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Table Name (e.g., Table 1 - Garden View)</label>
                <input type="text" name="table_name" required class="input-field" placeholder="Enter table name">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required class="input-field" placeholder="Describe features (e.g., Ground set, friendship size)"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" required class="input-field" value="4">
                </div>
                <div class="form-group">
                    <label>Table Image</label>
                    <input type="file" name="table_image" class="input-field">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="submit" name="add_table" class="btn">Save Table</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Table Modal -->
<div id="editTableModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h3>Edit Restaurant Table</h3>
            <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" style="border:none;background:none;cursor:pointer;font-size:20px;">&times;</button>
        </div>
        <form action="admin.php?tab=tables" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="existing_image" id="edit_existing_image">
            <div class="form-group">
                <label>Table Name</label>
                <input type="text" name="table_name" id="edit_name" required class="input-field">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="edit_desc" required class="input-field"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" id="edit_capacity" required class="input-field">
                </div>
                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file" name="table_image" class="input-field">
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status" class="input-field">
                    <option value="Available">Available</option>
                    <option value="Reserved">Reserved</option>
                    <option value="Occupied">Occupied</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="submit" name="update_table" class="btn">Update Table</button>
            </div>
        </form>
    </div>
</div>

<script>
function editTable(table) {
    document.getElementById('edit_id').value = table.id;
    document.getElementById('edit_name').value = table.table_name;
    document.getElementById('edit_desc').value = table.description;
    document.getElementById('edit_capacity').value = table.capacity;
    document.getElementById('edit_status').value = table.status;
    document.getElementById('edit_existing_image').value = table.image_url;
    document.getElementById('editTableModal').style.display = 'flex';
}
</script>
