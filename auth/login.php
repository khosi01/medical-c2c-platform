<?php
session_start();
require_once '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

   if ($user && password_verify($password, $user['password'])) {
    $otp = random_int(100000, 999999);

    $pdo->prepare("UPDATE users SET otp_code = ? WHERE id = ?")->execute([$otp, $user['id']]);

    $_SESSION['temp_user_id'] = $user['id'];
    $_SESSION['redirect_after_login'] = isset($_GET['redirect']) ? $_GET['redirect'] : '';

    mail($user['email'], "MedMarket - Login Code", "Your code: $otp", "From: no-reply@medmarket.com");

    header("Location: verify-otp.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login | MedMarket</title>
    
</head>
<body class="auth-page" style="background: linear-gradient(rgba(3, 104, 115, 0.4), rgba(3, 104, 115, 0.4)), url('../assets/images/sc.jpg'); background-size: cover; background-position: center;">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="glass-card shadow-lg p-22" style="background: var(--white-glass);
  backdrop-filter: blur(18px);
  border: 2px solid var(--primary-teal);
  border-radius: 20px;
  padding: 80px;
  height: 70vh;
  width:80vh;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8); margin: auto;">
            <h3 class="text-center mb-4" style="color: var(--dark-teal); font-weight: 700;font-family: poppins;">Sign In</h3>
            
            <?php if($error) echo "<div class='alert alert-danger py-2' style='font-size: 0.9rem;'>$error</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label" style="color: var(--dark-teal); font-weight: 500;">Email Address</label>
                    <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required style="border-color: #036873;">
                </div>
                
                <div class="mb-4">
                    <label class="form-label" style="color: var(--dark-teal); font-weight: 500;">Password</label>
                    <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required style="border-color: #036873;">
                </div>
                
                <button type="submit" class="btn-med w-100 py-2" style="background-color: #056873; color: var(--white); border: none; border-radius: 8px; padding: 12px 24px; font-weight: 600; transition: 0.3s ease; color:#ffff">Login to Account</button>
            </form>
            
            <div class="text-center mt-4">
                <small style="color: #056873;">Don't have an account? <a href="register.php" class="create-acc" style="color: #056873; font-weight: 600; text-decoration: none;">Create one</a></small>
            </div>
        </div>
    </div>
   <script src="../assets/js/script.js"></script>
</body>
</html>