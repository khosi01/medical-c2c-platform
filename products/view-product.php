<?php
session_start();
require_once '../config/db.php';

$base = '/medical-c2c-platform';

if (!isset($_GET['id'])) die("Product not found.");

$productId = $_GET['id'];
$stmt = $pdo->prepare("
    SELECT p.*, u.full_name, u.profile_pic, u.profession, u.location, u.created_at as user_since
    FROM products p JOIN users u ON p.seller_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) die("Product not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> | MedMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: #fdf5f8; font-family: 'Poppins', sans-serif; font-size: 14px; }

        .breadcrumb-trail { font-size: 0.82rem; color: #aaa; margin-bottom: 22px; }
        .breadcrumb-trail a { color: #aaa; text-decoration: none; }
        .breadcrumb-trail .current { color: #555; font-weight: 600; }

        .gallery-wrap { background: #e1eff2; border-radius: 22px; position: relative; min-height: 370px; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .gallery-wrap img { max-width: 100%; max-height: 340px; object-fit: contain; }
        .arrow-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 38px; height: 38px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,.08); color: #444; text-decoration: none; }
        .arrow-btn.left { left: 16px; } .arrow-btn.right { right: 16px; }
        .gallery-actions { position: absolute; top: 16px; right: 16px; display: flex; gap: 8px; }
        .act-btn { width: 36px; height: 36px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,.07); color: #036873; text-decoration: none; font-size: 1rem; }

        .thumb-row { display: flex; gap: 12px; margin: 14px 0 26px; }
        .thumb { width: 82px; height: 82px; border-radius: 12px; border: 1.5px solid #eee; background: #fff; padding: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .thumb.active { border-color: #036873; border-width: 2px; }
        .thumb img { width: 100%; height: 100%; object-fit: contain; border-radius: 6px; }

        .desc-card { background: #fff; border-radius: 20px; padding: 28px 30px; box-shadow: 0 2px 16px rgba(3,104,115,.04); }
        .desc-card h4 { font-weight: 800; color: #036873; font-size: 1.2rem; margin-bottom: 14px; }
        .desc-card p { font-size: 0.88rem; color: #4a4a4a; line-height: 1.75; }

        .s-card { background: #fff; border-radius: 18px; padding: 20px 22px; box-shadow: 0 2px 14px rgba(3,104,115,.05); border: 1px solid #f0f6f7; margin-bottom: 16px; }

        .price { font-size: 1.75rem; font-weight: 800; color: #036873; }
        .cond-badge { background: #e4f7f9; color: #036873; font-size: 0.72rem; font-weight: 700; padding: 4px 11px; border-radius: 7px; }
        .btn-cart { width: 100%; background: #04a0af; color: #fff; border: none; border-radius: 10px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; justify-content: center; gap: 8px; margin: 14px 0 9px; cursor: pointer; }
        .btn-cart:hover { background: #028a97; }
        .btn-msg { width: 100%; background: #fff; color: #333; border: 1.5px solid #ddd; border-radius: 10px; padding: 11px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.88rem; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
        .btn-msg:hover { border-color: #04a0af; color: #036873; }
        .meta-list { margin-top: 14px; display: flex; flex-direction: column; gap: 7px; font-size: 0.78rem; color: #666; }
        .meta-list span { display: flex; align-items: center; gap: 8px; }
        .meta-list i { color: #04a0af; }

        .s-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #aaa; margin-bottom: 13px; }
        .seller-avatar { width: 44px; height: 44px; border-radius: 10px; overflow: hidden; background: #036873; color: #fff; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .seller-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .seller-name { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; margin: 0; }
        .seller-role { font-size: 0.72rem; color: #888; margin: 2px 0 5px; }
        .stars { color: #f4c542; font-size: 0.7rem; }
        .seller-stats { border-top: 1px solid #f0f6f7; margin-top: 13px; padding-top: 11px; display: flex; flex-direction: column; gap: 6px; font-size: 0.76rem; }
        .stat-row { display: flex; justify-content: space-between; }
        .stat-row .sk { color: #aaa; } .stat-row .sv { font-weight: 700; }
        .verified-pill { background: #e4f7f9; color: #036873; font-size: 0.8rem; font-weight: 700; padding: 9px 14px; border-radius: 10px; display: flex; align-items: center; gap: 7px; margin-top: 12px; }

        .s-card.safety { background: #fafefe; }
        .safety-title { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; color: #036873; display: flex; align-items: center; gap: 6px; margin-bottom: 10px; }
        .safety ul { margin: 0; padding-left: 16px; font-size: 0.74rem; color: #666; line-height: 1.65; }
    </style>
</head>
<body>

<?php include '../includes/navbar-browse.php'; ?>

<div class="container py-4">

    <div class="breadcrumb-trail">
        <a href="<?php echo $base; ?>">Home</a> /
        <a href="#"><?php echo htmlspecialchars($product['category'] ?? 'Equipment'); ?></a> /
        <span class="current"><?php echo htmlspecialchars($product['title']); ?></span>
    </div>

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-lg-7">
            <div class="gallery-wrap">
                <?php if (!empty($product['image_path'])): ?>
                    <img id="mainImg" src="<?php echo $base; ?>/uploads/products/<?php echo htmlspecialchars($product['image_path']); ?>" alt="">
                <?php else: ?>
                    <i class="bi bi-box-seam" style="font-size:5rem;color:rgba(3,104,115,.2);"></i>
                <?php endif; ?>
                <a href="#" class="arrow-btn left"><i class="bi bi-chevron-left"></i></a>
                <a href="#" class="arrow-btn right"><i class="bi bi-chevron-right"></i></a>
                <div class="gallery-actions">
                    <a href="#" class="act-btn" id="wishBtn"><i class="bi bi-heart"></i></a>
                    <a href="#" class="act-btn"><i class="bi bi-share"></i></a>
                </div>
            </div>

            <div class="thumb-row">
                <div class="thumb active">
                    <img src="<?php echo $base; ?>/uploads/products/<?php echo htmlspecialchars($product['image_path'] ?? ''); ?>" onerror="this.src='<?php echo $base; ?>/assets/images/placeholder.jpg'">
                </div>
                <div class="thumb"><img src="<?php echo $base; ?>/assets/images/placeholder.jpg"></div>
                <div class="thumb"><img src="<?php echo $base; ?>/assets/images/placeholder.jpg"></div>
            </div>

            <div class="desc-card">
                <h4>Product Description</h4>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-5">

            <div class="s-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="price">R<?php echo number_format($product['price'], 2); ?></span>
                    <span class="cond-badge"><?php echo htmlspecialchars($product['p_condition']); ?></span>
                </div>
                <button class="btn-cart"><i class="bi bi-cart-plus"></i> Add to Cart</button>
                <a href="<?php echo $base; ?>/messages.php?buyer_initiate=<?php echo $product['seller_id']; ?>&prod=<?php echo $product['id']; ?>" class="btn-msg">
                    <i class="bi bi-chat-dots"></i> Message Seller
                </a>
                <div class="meta-list">
                    <span><i class="bi bi-geo-alt-fill"></i><?php echo htmlspecialchars($product['location'] ?? 'Johannesburg, Gauteng'); ?></span>
                    <span><i class="bi bi-shield-check"></i>Buyer Protection Included</span>
                    <span><i class="bi bi-tags-fill"></i>Competitive Market Pricing</span>
                </div>
            </div>

            <div class="s-card">
                <div class="s-label">Seller Information</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="seller-avatar">
                        <?php if (!empty($product['profile_pic'])): ?>
                            <img src="<?php echo $base; ?>/uploads/profiles/<?php echo htmlspecialchars($product['profile_pic']); ?>">
                        <?php else: ?>
                            <?php echo strtoupper(substr($product['full_name'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="seller-name"><?php echo htmlspecialchars($product['full_name']); ?></p>
                        <p class="seller-role"><?php echo htmlspecialchars($product['profession'] ?? 'Medical Professional'); ?></p>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <span style="color:#aaa;font-size:.68rem;margin-left:4px;">(100 reviews)</span>
                        </div>
                    </div>
                </div>
                <div class="seller-stats">
                    <div class="stat-row"><span class="sk">Response Time</span><span class="sv">Within 3 hours</span></div>
                    <div class="stat-row"><span class="sk">Items Listed</span><span class="sv">12 Active</span></div>
                    <div class="stat-row"><span class="sk">Member Since</span><span class="sv"><?php echo date('F Y', strtotime($product['user_since'] ?? 'now')); ?></span></div>
                </div>
                <div class="verified-pill"><i class="bi bi-patch-check-fill"></i> Verified Seller</div>
            </div>

            <div class="s-card safety">
                <div class="safety-title"><i class="bi bi-shield-shaded"></i> Safety Tips</div>
                <ul>
                    <li>Meet in a secure public healthcare facility or public space.</li>
                    <li>Inspect item thoroughly before completing payment.</li>
                    <li>Transact safely inside our platform.</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.thumb').forEach(t => {
        t.addEventListener('click', () => {
            document.querySelectorAll('.thumb').forEach(x => x.classList.remove('active'));
            t.classList.add('active');
            const src = t.querySelector('img').src;
            const main = document.getElementById('mainImg');
            if (main) main.src = src;
        });
    });
    document.getElementById('wishBtn').addEventListener('click', e => {
        e.preventDefault();
        const i = e.currentTarget.querySelector('i');
        i.classList.toggle('bi-heart');
        i.classList.toggle('bi-heart-fill');
        i.style.color = i.classList.contains('bi-heart-fill') ? '#e05' : '';
    });
</script>
</body>
</html>