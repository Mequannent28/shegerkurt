<?php
// Blog Management Tab
$blogs = $pdo->query("SELECT * FROM blogs ORDER BY created_date DESC")->fetchAll();
$blog_count = count($blogs);
?>
<div
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 4px;">Blog & News Management</h2>
        <p style="color: rgba(255,255,255,0.7); font-size: 14px;">
            <i class="fa-solid fa-newspaper" style="color:var(--primary); margin-right:6px;"></i>
            <?= $blog_count ?> published article<?= $blog_count !== 1 ? 's' : '' ?> on website
        </p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <button onclick="document.getElementById('addBlogModal').style.display='flex';" class="btn"
            style="background: var(--primary); color: #fff; border-radius: 10px; padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-plus"></i> Write New Article
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
    <?php foreach ($blogs as $b): ?>
        <div class="card" style="padding: 0; overflow: hidden; position: relative; border-radius: 16px;">
            <div style="height: 180px; overflow: hidden; position: relative;">
                <img src="<?= htmlspecialchars($b['image_url']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <span style="position: absolute; top: 15px; left: 15px; background: var(--primary); color: #fff; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                    <?= htmlspecialchars($b['category']) ?>
                </span>
            </div>
            <div style="padding: 20px;">
                <div style="display: flex; gap: 10px; font-size: 11px; color: #64748b; margin-bottom: 10px; font-weight: 600;">
                    <span><i class="fa-regular fa-calendar"></i> <?= date("M d, Y", strtotime($b['created_date'])) ?></span>
                    <span><i class="fa-regular fa-user"></i> <?= htmlspecialchars($b['author']) ?></span>
                </div>
                <h3 style="font-size: 18px; line-height: 1.4; color: #1e293b; margin-bottom: 10px;">
                    <?= htmlspecialchars($b['title']) ?>
                </h3>
                <p style="font-size: 13px; color: #64748b; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 20px;">
                    <?= htmlspecialchars($b['content']) ?>
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f1f5f9; gap: 8px;">
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="viewBlog(<?= htmlspecialchars(json_encode($b)) ?>)" style="background: #f1f5f9; color: #475569; padding: 6px 12px; border:none; border-radius: 8px; font-weight: 700; font-size: 12px; cursor:pointer; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-eye"></i> View
                        </button>
                        <a href="?tab=blogs&edit=<?= $b['id'] ?>" style="background: #ecfdf5; color: #059669; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-pencil"></i> Edit
                        </a>
                    </div>
                    <button onclick="modernDelete('delete_blog', '<?= $b['id'] ?>', '<?= htmlspecialchars($b['title'], ENT_QUOTES) ?>', 'Blog Post')" 
                            style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 12px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function viewBlog(b) {
        document.getElementById('view_blog_title').innerText = b.title;
        document.getElementById('view_blog_author').innerText = b.author;
        document.getElementById('view_blog_date').innerText = new Date(b.created_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        document.getElementById('view_blog_category').innerText = b.category;
        document.getElementById('view_blog_content').innerText = b.content;
        document.getElementById('view_blog_img').src = b.image_url;
        document.getElementById('viewBlogModal').style.display = 'flex';
    }
</script>

<!-- View Blog Modal -->
<div class="modal-overlay" id="viewBlogModal" style="display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);">
    <div class="modal-content" style="max-width: 800px; max-height: 90vh; overflow-y: auto; padding: 0; border: none; box-shadow: 0 30px 60px rgba(0,0,0,0.5);">
        <div style="position: relative; height: 350px;">
            <img id="view_blog_img" style="width: 100%; height: 100%; object-fit: cover;">
            <div style="position: absolute; top: 20px; right: 20px;">
                <button type="button" onclick="document.getElementById('viewBlogModal').style.display='none';" 
                    style="background: rgba(255,255,255,0.9); border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #1e293b; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(0deg, #fff, transparent); height: 80px;"></div>
        </div>
        <div style="padding: 0 40px 40px; background: #fff;">
            <div style="margin-top: -20px; position: relative; z-index: 5;">
                <span id="view_blog_category" style="background: var(--primary); color: #fff; padding: 6px 15px; border-radius: 10px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;"></span>
            </div>
            <h2 id="view_blog_title" style="font-size: 32px; color: #1e293b; margin: 25px 0 15px; font-weight: 800; line-height: 1.2;"></h2>
            <div style="display: flex; gap: 20px; color: #64748b; font-size: 14px; margin-bottom: 30px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                <span><i class="fa-regular fa-user" style="color:var(--primary); margin-right:5px;"></i> <strong id="view_blog_author" style="color:#334155;"></strong></span>
                <span><i class="fa-regular fa-calendar" style="color:var(--primary); margin-right:5px;"></i> <span id="view_blog_date"></span></span>
            </div>
            <div id="view_blog_content" style="font-size: 16px; color: #475569; line-height: 1.8; white-space: pre-wrap;"></div>
            <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 30px; text-align: right;">
                <button type="button" onclick="document.getElementById('viewBlogModal').style.display='none';" class="btn" style="background: #1e293b; color: #fff; padding: 12px 30px; border-radius: 12px; font-weight: 700;">Close Article</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Blog Modal -->
<div class="modal-overlay" id="addBlogModal" style="display: none;">
    <div class="modal-content" style="max-width: 650px;">
        <div class="card-header" style="border-bottom:none;">
            <span class="card-title">Write New Blog Article</span>
            <button onclick="document.getElementById('addBlogModal').style.display='none';" style="background:none; border:none; font-size:24px; color:#94a3b8; cursor:pointer;">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="add_blog" value="1">
            <div class="form-row">
                <div>
                    <label>Article Title</label>
                    <input type="text" name="title" placeholder="e.g. Secret Recipes of Sheger Kurt" required>
                </div>
                <div>
                    <label>Category</label>
                    <input type="text" name="category" placeholder="e.g. Food, News, Drink" required>
                </div>
            </div>
            <div class="form-row" style="margin-top:15px;">
                <div>
                    <label>Author Name</label>
                    <input type="text" name="author" value="<?= $_SESSION['admin_name'] ?>">
                </div>
                <div>
                    <label>Published Date</label>
                    <input type="date" name="created_date" value="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div style="margin-top:15px;">
                <label>Main Image URL (or upload below)</label>
                <input type="text" name="image_url" placeholder="Paste image link here">
                <input type="file" name="blog_photo" style="margin-top:8px;">
            </div>
            <div style="margin-top:15px;">
                <label>Article Content</label>
                <textarea name="content" rows="6" placeholder="Write your story here..." required></textarea>
            </div>
            <div style="margin-top:25px; display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="document.getElementById('addBlogModal').style.display='none';" class="btn" style="background:#f1f5f9;">Cancel</button>
                <button type="submit" class="btn btn-primary">Publish Now</button>
            </div>
        </form>
    </div>
</div>
