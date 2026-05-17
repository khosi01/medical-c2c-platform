<?php
session_start();
require_once '../config/db.php';
require_once '../includes/auth-check.php';

$userId = $_SESSION['user_id'];

if (!isset($_GET['id'])) die("Invalid request.");

$productId = $_GET['id'];

// Verify ownership
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$productId, $userId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) die("Access denied.");

// Delete all images from product_images table
$imgs = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$imgs->execute([$productId]);
foreach ($imgs->fetchAll(PDO::FETCH_COLUMN) as $img) {
    $file = '../uploads/products/' . $img;
    if (file_exists($file)) unlink($file);
}

// Fallback: also delete the cover stored on the product row itself (in case product_images is empty)
if (!empty($product['image_path'])) {
    $file = '../uploads/products/' . $product['image_path'];
    if (file_exists($file)) unlink($file);
}

// Delete the product (product_images rows are removed automatically via ON DELETE CASCADE)
$pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?")->execute([$productId, $userId]);

header("Location: my-products.php");
exit();
?>