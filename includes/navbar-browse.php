<?php

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$base = '/medical-c2c-platform';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products | MedMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>../assets/css/style.css">
    </head>
<body>
 

<nav style="background:white; border-bottom:1px solid #e4f4f6; box-shadow:0 2px 12px rgba(3,104,115,0.07); position:sticky; top:0; z-index:100;">
    <div style="display:flex; align-items:center; gap:20px; padding:12px 30px; max-width:1400px; margin:0 auto;">

       
        <a href="<?php echo $base; ?>/index.php" style="display:flex; align-items:center; gap:8px; text-decoration:none; flex-shrink:0;">
            <img src="<?php echo $base; ?>/assets/images/Logo.jpg" alt="MedMarket" width="36" class="rounded" style="border:1px solid rgba(3,104,115,0.5); border-radius:20%;">
            <span style="font-family:'DM Serif Display',serif; color:#036873; font-size:1.1rem;">Med<em>Market</em></span>
        </a>

        <!-- Search -->
        <form method="GET" action="<?php echo $base; ?>/products/browse.php" style="flex:1;">
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#8bbfc4;"></i>
                <input type="text" name="q"
                    value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                    placeholder="Search for medical books, equipment, and materials..."
                    style="width:100%; padding:10px 16px 10px 40px; border:1.5px solid #c8edf0; border-radius:50px; font-family:'Poppins',sans-serif; font-size:0.88rem; background:#f4fcfd; outline:none; color:#333;">
            </div>
        </form>



        <div style="display:flex; align-items:center; gap:16px; flex-shrink:0;">
            <?php if ($isLoggedIn): ?>
                <a href="<?php echo $base; ?>/products/add-product.php"
                   style="display:inline-flex; align-items:center; gap:6px; background:#036873; color:white; padding:9px 20px; border-radius:50px; text-decoration:none; font-family:'Poppins',sans-serif; font-size:0.88rem; font-weight:600;">
                    <i class="bi bi-plus-lg"></i> Sell Item
                </a>
                <a href="<?php echo $base; ?>/messages.php"
                   style="display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#036873; font-family:'Poppins',sans-serif; font-size:0.72rem; font-weight:500; gap:2px;">
                    <i class="bi bi-chat-square-text" style="font-size:1.3rem;"></i>
                    Messages
                </a>
                <a href="<?php echo $base; ?>/user/profile.php"
                   style="display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#036873; font-family:'Poppins',sans-serif; font-size:0.72rem; font-weight:500; gap:2px;">
                    <i class="bi bi-person-circle" style="font-size:1.3rem;"></i>
                    Profile
                </a>
            <?php else: ?>
                <a href="<?php echo $base; ?>/auth/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>"
                   style="color:#036873; font-weight:600; text-decoration:none; font-size:0.9rem; padding:8px 16px; border-radius:50px; border:1.5px solid #036873; font-family:'Poppins',sans-serif;">
                    Sign In
                </a>
                <a href="<?php echo $base; ?>/auth/register.php"
                   style="background:#036873; color:white; font-weight:600; text-decoration:none; font-size:0.9rem; padding:9px 18px; border-radius:50px; font-family:'Poppins',sans-serif;">
                    Create Account
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
 

</body>
</html>
 