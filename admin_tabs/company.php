<?php 
$c = $pdo->query("SELECT * FROM company_info WHERE id=1")->fetch(); 
$msg = $_SESSION['success_msg'] ?? '';
unset($_SESSION['success_msg']);
?>
<div style="max-width: 1000px; margin: 0 auto; padding: 20px 0 60px;">
    
    <?php if($msg): ?>
        <div style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #bbf7d0; font-weight: 600;">
            <i class="fa-solid fa-circle-check"></i> <?= $msg ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_company" value="1">

        <!-- 1. Header & Branding -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-building"></i> General Branding & Contact</span>
            </div>
            <div style="padding: 25px;">
                <div class="form-row">
                    <div>
                        <label>Restaurant Name</label>
                        <input type="text" name="company_name" value="<?= htmlspecialchars($c['company_name'] ?? 'Sheger Kurt') ?>" required>
                    </div>
                    <div>
                        <label>Contact Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($c['phone'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 15px;">
                    <div>
                        <label>Contact Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($c['email'] ?? '') ?>">
                    </div>
                    <div>
                        <label>Physical Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($c['address'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row" style="margin-top: 15px;">
                    <div style="flex: 2;">
                        <label><i class="fa-solid fa-location-dot" style="color:#ef4444;"></i> Google Maps Embed URL (iframe src)</label>
                        <input type="text" name="google_maps_url" value="<?= htmlspecialchars($c['google_maps_url'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?pb=...">
                        <small style="font-size: 10px; color: #64748b;">Copy the 'src' from the Google Maps embed code.</small>
                    </div>
                    <div style="flex: 1;">
                        <label><i class="fa-solid fa-star" style="color:#f59e0b;"></i> Google Rating</label>
                        <input type="number" step="0.1" name="google_rating" value="<?= htmlspecialchars($c['google_rating'] ?? '4.5') ?>">
                    </div>
                    <div style="flex: 1;">
                        <label><i class="fa-solid fa-users" style="color:#64748b;"></i> Total Reviews</label>
                        <input type="number" name="google_rating_count" value="<?= htmlspecialchars($c['google_rating_count'] ?? '100') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Hero Section -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-bolt"></i> Hero Section (Main Banner)</span>
            </div>
            <div style="padding: 25px;">
                <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <label>Main Banner Image</label>
                        <div style="height: 200px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; position: relative;">
                            <img id="heroPreview" src="<?= htmlspecialchars($c['hero_image'] ?? './assets/images/hero-banner.png') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <input type="file" name="hero_image" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'heroPreview')">
                        </div>
                        <input type="text" name="hero_image_url" placeholder="Or Image URL" value="<?= htmlspecialchars($c['hero_image'] ?? '') ?>" style="margin-top:10px; font-size: 12px;">
                    </div>
                    <div style="flex: 2; min-width: 300px;">
                        <label>Main Title (Big White Text)</label>
                        <input type="text" name="hero_title" value="<?= htmlspecialchars($c['hero_title'] ?? '') ?>" placeholder="e.g. Traditional Ethiopian Kurt & Bar!">
                        <label style="margin-top:15px; display:block;">Sub-Title (Yellow Text Above Title)</label>
                        <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($c['hero_subtitle'] ?? '') ?>" placeholder="e.g. Eat Sleep And">
                        <label style="margin-top:15px; display:block;">Description (Small Text Below Title)</label>
                        <textarea name="about_text" rows="3"><?= htmlspecialchars($c['about_text'] ?? '') ?></textarea>
                        <label style="margin-top:15px; display:block;">Button Text</label>
                        <input type="text" name="hero_button_text" value="<?= htmlspecialchars($c['hero_button_text'] ?? 'Book A Table') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. About Us Section -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-info-circle"></i> About Us Section</span>
            </div>
            <div style="padding: 25px;">
                <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <label>About Image</label>
                        <div style="height: 200px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; position: relative;">
                            <img id="aboutPreview" src="<?= htmlspecialchars($c['about_image_main'] ?? './assets/images/about-banner.png') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <input type="file" name="about_image" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'aboutPreview')">
                        </div>
                        <input type="text" name="about_image_url" placeholder="Or Image URL" value="<?= htmlspecialchars($c['about_image_main'] ?? '') ?>" style="margin-top:10px; font-size: 12px;">
                    </div>
                    <div style="flex:2;">
                        <label>About Headline</label>
                        <input type="text" name="about_subtitle" value="<?= htmlspecialchars($c['about_subtitle'] ?? '') ?>" placeholder="e.g. Sheger Kurt, Traditional Meat, and Best Bar in Town!">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Delivery Section -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-truck"></i> Delivery Section</span>
            </div>
            <div style="padding: 25px;">
                <div class="form-row">
                    <div>
                        <label>Delivery Title</label>
                        <input type="text" name="delivery_title" value="<?= htmlspecialchars($c['delivery_title'] ?? '') ?>">
                    </div>
                </div>

                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px;">
                    <div style="flex: 1;">
                        <label>Delivery Rider Image (Motorcycle)</label>
                        <div style="height: 120px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; position: relative; background: #fdfaf7;">
                            <img id="deliveryRiderPreview" src="<?= htmlspecialchars($c['delivery_rider_image'] ?? './assets/images/delivery-boy.svg') ?>" style="width: 100%; height: 100%; object-fit: contain;">
                            <input type="file" name="delivery_rider_image" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'deliveryRiderPreview')">
                        </div>
                        <input type="text" name="delivery_rider_image_url" placeholder="Or Image URL" value="<?= htmlspecialchars($c['delivery_rider_image'] ?? '') ?>" style="margin-top:10px; font-size: 12px; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label>Delivery Background Shape</label>
                        <div style="height: 120px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; position: relative; background: #fdfaf7;">
                            <img id="deliveryBgPreview" src="<?= htmlspecialchars($c['delivery_image'] ?? './assets/images/delivery-banner-bg.png') ?>" style="width: 100%; height: 100%; object-fit: contain;">
                            <input type="file" name="delivery_image" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'deliveryBgPreview')">
                        </div>
                        <input type="text" name="delivery_image_url" placeholder="Or Image URL" value="<?= htmlspecialchars($c['delivery_image'] ?? '') ?>" style="margin-top:10px; font-size: 12px; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <label style="margin-top:15px; display:block;">Delivery Description</label>
                <textarea name="delivery_text" rows="3"><?= htmlspecialchars($c['delivery_text'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- 5. Special Offers (Promos) - Demo View -->
        <?php $promo_list = $pdo->query("SELECT * FROM promo_items LIMIT 5")->fetchAll(); ?>
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-tags"></i> Special Offers (Promos) Demo</span>
            </div>
            <div style="padding: 25px;">
                <p style="font-size: 13px; color: #666; margin-bottom: 15px;">Note: These are seeded from your website's promo section. You can manage them in the future via a dedicated Promo tab, but here is what the website currently shows:</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px;">
                    <?php foreach($promo_list as $p): ?>
                        <div style="background: #fdfaf7; padding: 10px; border-radius: 10px; border: 1px solid #ff9d2d33;">
                            <img src="<?= htmlspecialchars($p['image_url']) ?>" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;">
                            <h4 style="font-size: 14px; margin: 10px 0 5px; color: #1a1512;"><?= htmlspecialchars($p['title']) ?></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>
        
        <!-- 6. Footer & Opening Hours -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-clock"></i> Footer & Opening Hours</span>
            </div>
            <div style="padding: 25px;">
                <h4 style="margin-bottom: 15px; color: #1e293b; font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Footer Illustration (City & Bicycle Image)</h4>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 25px;">
                    <div style="flex: 1; max-width: 400px;">
                        <div style="height: 120px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; position: relative; background: #fdfaf7;">
                            <img id="footerBgPreview" src="<?= htmlspecialchars($c['footer_bg_image'] ?? './assets/images/footer-illustration.png') ?>" style="width: 100%; height: 100%; object-fit: contain;">
                            <input type="file" name="footer_bg_image" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'footerBgPreview')">
                        </div>
                        <input type="text" name="footer_bg_image_url" placeholder="Or Image URL" value="<?= htmlspecialchars($c['footer_bg_image'] ?? '') ?>" style="margin-top:10px; font-size: 12px; width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div style="flex: 2; font-size: 13px; color: #64748b; line-height: 1.5;">
                        <p><strong>Pro Tip:</strong> This is the large background image displaying the city skyline and the orange delivery rider. Use a wide PNG with a transparent background for best results.</p>
                    </div>
                </div>

                <div class="form-row">
                    <div style="flex: 1;">
                        <label>Footer About Text</label>
                        <textarea name="footer_text" rows="3" placeholder="e.g. Experience the authentic taste of Ethiopian Kurt..."><?= htmlspecialchars($c['footer_text'] ?? 'Experience the authentic taste of Ethiopian Kurt in the heart of the city.') ?></textarea>
                        
                        <label style="margin-top:15px; display:block;">Copyright Text</label>
                        <input type="text" name="copyright_text" value="<?= htmlspecialchars($c['copyright_text'] ?? '© 2026 Sheger Kurt All Rights Reserved.') ?>">
                    </div>
                    <div style="flex: 1;">
                        <label>Opening Hours 1</label>
                        <input type="text" name="opening_hours_1" value="<?= htmlspecialchars($c['opening_hours_1'] ?? 'Monday-Friday: 08:00-22:00') ?>" style="margin-bottom: 10px;">
                        
                        <label>Opening Hours 2</label>
                        <input type="text" name="opening_hours_2" value="<?= htmlspecialchars($c['opening_hours_2'] ?? 'Tuesday 4PM: Till Mid Night') ?>" style="margin-bottom: 10px;">
                        
                        <label>Opening Hours 3</label>
                        <input type="text" name="opening_hours_3" value="<?= htmlspecialchars($c['opening_hours_3'] ?? 'Saturday: 10:00-16:00') ?>">
                    </div>
                </div>
                
                <h4 style="margin-top: 25px; margin-bottom: 15px; color: #1e293b; font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Social Media Links</h4>
                <div class="form-row">
                    <div>
                        <label><i class="fa-brands fa-facebook" style="color: #1877f2;"></i> Facebook URL</label>
                        <input type="url" name="facebook" value="<?= htmlspecialchars($c['facebook'] ?? '') ?>" placeholder="https://facebook.com/shegerkurt">
                    </div>
                    <div>
                        <label><i class="fa-brands fa-twitter" style="color: #1da1f2;"></i> Twitter URL</label>
                        <input type="url" name="twitter" value="<?= htmlspecialchars($c['twitter'] ?? '') ?>" placeholder="https://twitter.com/shegerkurt">
                    </div>
                    <div>
                        <label><i class="fa-brands fa-instagram" style="color: #e4405f;"></i> Instagram URL</label>
                        <input type="url" name="instagram" value="<?= htmlspecialchars($c['instagram'] ?? '') ?>" placeholder="https://instagram.com/shegerkurt">
                    </div>
                </div>

            </div>
        </div>
        <!-- 7. Developer Attribution Settings -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border); background: linear-gradient(to right, #ffffff, #fdfaf7);">
            <div class="card-header" style="background: rgba(255,157,45,0.05); border-bottom: 1px solid var(--border);">
                <span class="card-title" style="color: var(--deep-saffron);"><i class="fa-solid fa-code"></i> Developer Profile</span>
            </div>
            <div style="padding: 25px;">
                <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <label>Developer Photo</label>
                        <div style="height: 180px; border: 2px dashed #ff9d2d88; border-radius: 90px; overflow: hidden; position: relative; width: 180px; margin: 0 auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                            <img id="devPreview" src="<?= !empty($c['dev_photo']) ? htmlspecialchars($c['dev_photo']) : './uploads/admin/dev_mequannent.jpg' ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <input type="hidden" name="existing_dev_photo" value="<?= htmlspecialchars($c['dev_photo'] ?? '') ?>">
                            <input type="file" name="dev_photo" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'devPreview')">
                        </div>
                        <p style="text-align: center; font-size: 11px; color: #64748b; margin-top: 10px;">Recommended: 1:1 Aspect Ratio (Square)</p>
                    </div>
                    <div style="flex: 2; min-width: 300px;">
                        <div class="form-row">
                            <div style="flex: 1;">
                                <label>Developer Name</label>
                                <input type="text" name="dev_name" value="<?= htmlspecialchars($c['dev_name'] ?? 'Mequannent Gashaw') ?>" placeholder="Full Name">
                            </div>
                            <div style="flex: 1;">
                                <label>Developer Phone (WhatsApp)</label>
                                <input type="text" name="dev_phone" value="<?= htmlspecialchars($c['dev_phone'] ?? '') ?>" placeholder="e.g. +251 920 000 000">
                            </div>
                        </div>
                        <div class="form-row" style="margin-top: 15px;">
                            <div style="flex: 1;">
                                <label><i class="fa-brands fa-telegram" style="color: #0088cc;"></i> Telegram Username</label>
                                <input type="text" name="dev_telegram" value="<?= htmlspecialchars($c['dev_telegram'] ?? 'mequannent_gashaw') ?>" placeholder="e.g. username (without @)">
                            </div>
                            <div style="flex: 1;">
                                <label><i class="fa-brands fa-linkedin" style="color: #0077b5;"></i> LinkedIn Profile</label>
                                <input type="url" name="dev_linkedin" value="<?= htmlspecialchars($c['dev_linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 8. Payment Account Details -->
        <div class="card" style="border-radius: 16px; margin-bottom: 30px; border: 1px solid var(--border);">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid var(--border);">
                <span class="card-title"><i class="fa-solid fa-credit-card"></i> Payment & Bank Details</span>
            </div>
            <div style="padding: 25px;">
                <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">These details are sent automatically to customers when they place an order via the chatbot.</p>
                <div class="form-row">
                    <div style="flex: 1;">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" value="<?= htmlspecialchars($c['bank_name'] ?? 'Commercial Bank of Ethiopia (CBE)') ?>">
                    </div>
                    <div style="flex: 1;">
                        <label>Account Holder Name</label>
                        <input type="text" name="account_name" value="<?= htmlspecialchars($c['account_name'] ?? 'Sheger Kurt Restaurant') ?>">
                    </div>
                </div>
                <div class="form-row" style="margin-top: 15px;">
                    <div style="flex: 1;">
                        <label>Account Number</label>
                        <input type="text" name="account_number" value="<?= htmlspecialchars($c['account_number'] ?? '1000123456789') ?>">
                    </div>
                    <div style="flex: 1;">
                        <label>Payment QR Code (Link or Upload)</label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <img id="qrPreview" src="<?= !empty($c['qr_code_image']) ? htmlspecialchars($c['qr_code_image']) : 'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg' ?>" style="width: 50px; height: 50px; border-radius: 5px; object-fit: contain; border: 1px solid #ddd;">
                            <div style="flex: 1; position: relative; border: 1px solid #ddd; padding: 10px; border-radius: 8px; background: #fff; cursor: pointer; text-align: center;">
                                <span style="font-size: 12px; color: #666;">Change QR</span>
                                <input type="file" name="qr_code_file" style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="previewImg(this, 'qrPreview')">
                            </div>
                        </div>
                        <input type="text" name="qr_code_image" value="<?= htmlspecialchars($c['qr_code_image'] ?? '') ?>" placeholder="Or enter QR Image URL manually" style="margin-top: 8px; font-size: 12px;">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; border-radius: 12px; font-size: 18px; font-weight: 700; box-shadow: 0 10px 20px rgba(255,157,45,0.3);">
            <i class="fa-solid fa-save"></i> SAVE ALL WEBSITE CHANGES
        </button>
        </div>

    </form>
</div>

<script>
    function previewImg(input, targetId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(targetId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>