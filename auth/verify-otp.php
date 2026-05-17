<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = trim($_POST['otp']);
    $userId = $_SESSION['temp_user_id'] ?? null;



    if (!$userId) {
        header("Location: login.php?error=session_expired");
        exit();
    }

if (empty($enteredOtp)) {
        $error = "No code received. Please try again.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND otp_code = ?");
        $stmt->execute([$userId, $enteredOtp]);
        $user = $stmt->fetch();

    if ($user) {
        $pdo->prepare("UPDATE users SET otp_code = NULL WHERE id = ?")->execute([$userId]);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];

        $redirect = $_SESSION['redirect_after_login'] ?? '';
        unset($_SESSION['temp_user_id']);
        unset($_SESSION['redirect_after_login']);

        if (!empty($redirect) && strpos($redirect, '/medical-c2c-platform/') === 0) {
            header("Location: " . $redirect);
        } else {
            header("Location: ../index.php");
        }
        exit(); 

    } else {
        $error = "Invalid code. Please try again."; 
    }}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Identity | MedMarket</title>
   
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page" style="min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(rgba(57, 57, 57, 0.4), rgba(53, 53, 53, 0.4)), url('../assets/images/luis.jpg'); background-size: cover; background-position: center; ">
    <div class="glass-card text-center" style="

background: var(--glass-white);
  backdrop-filter: blur(18px);
  border: 2px solid var(--primary-teal);
  border-radius: 20px;
  margin: auto;
  padding: 80px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);">
       
        <h2 style="color: var(--dark-teal); font-weight: 700;">Security Verification</h2>
       <p style="color: white;">Enter the 6-digit code sent to your medical email.</p>

        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST" action="verify-otp.php" id="otp-form">
            <input type="hidden" name="otp" id="final-otp">
            
    <div class="otp-input-container d-flex justify-content-between mb-4" style="padding:20px; ">
       <input type="text" class="otp-field" maxlength="1" pattern="\d*">
        <input type="text" class="otp-field" maxlength="1" pattern="\d*">
        <input type="text" class="otp-field" maxlength="1" pattern="\d*">
        <input type="text" class="otp-field" maxlength="1" pattern="\d*">
        <input type="text" class="otp-field" maxlength="1" pattern="\d*">
        <input type="text" class="otp-field" maxlength="1" pattern="\d*">
    </div>
            <button type="submit" class="btn-med w-100 py-3" style="color:white;">Verify Account</button>
        </form>

        <div class="mt-4">
           <p class="small" style="color: white;">Didn't receive the code? <br>
            <a id="resend-link" href="#" class="resend-link disabled" style="color: var(--dark-teal); pointer-events: none; text-decoration: underline;">
            Resend OTP in <span id="timer">01:59</span></a></p>
        </div>
    </div>
    <script>
    console.log('form:', document.getElementById('otp-form'));
    console.log('finalInput:', document.getElementById('final-otp'));
</script>
<script src="../assets/js/script.js"></script>
</body>
</html>