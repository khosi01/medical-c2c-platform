<?php
session_start();
require_once '../config/db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $prof = $_POST['profession'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $msg = "<div class='alert alert-danger text-center'>Email already registered.</div>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, profession) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$name, $email, $pass, $prof])) {
            $userId = $pdo->lastInsertId();
            $otp = random_int(100000, 999999);

            $pdo->prepare("UPDATE users SET otp_code = ? WHERE id = ?")->execute([$otp, $userId]);

            $_SESSION['temp_user_id'] = $userId;
            $_SESSION['redirect_after_login'] = ''; 

           // mail($email, "MedMarket | Verify Your Account", "Your code: $otp", "From: no-reply@medmarket.com");

             echo "<!DOCTYPE html>
    <html><head></head>
    <body>
    <script>
        alert('Welcome! Your verification code is: $otp');
        window.location.href = 'verify-otp.php';
    </script>
    </body></html>";
    exit();
}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../assets/css/style.css">
   <title>Register | MedMarket</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh; background: linear-gradient(rgba(3, 104, 115, 0.4), rgba(3, 104, 115, 0.4)),
 url('../assets/images/Image.jpeg'); background-repeat: no-repeat; background-position: center; background-attachment: fixed; background-size: cover;">
    <div class="glass-container" style="width: 100%; margin: 0 auto; max-width: 700px; border: 2px solid #077c89; padding:150px; border-radius: 15px; ">
        <h2 class="text-center mb-4" style="color: #077c89; padding-bottom: 20px;font-weight:bold; font-size: 30px; font-family: poppins;">Create Account</h2>
        <form method="POST">
            <input type="text" name="full_name" class="form-control mb-3" placeholder="Full Name" required style="border: 1px solid #077c89; background-color: #eefdff;">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required style="border: 1px solid #077c89; background-color: #eefdff;">
            <select name="profession" class="form-select mb-3" required style="border: 1px solid #077c89; background-color: #eefdff;">
              <option value="" disabled selected >
        Profession
    </option>

    <option value="Doctor/Specialist">
        Doctor / Specialist
    </option>

    <option value="Nurse">
        Nurse
    </option>

    <option value="Medical Student">
        Medical Student
    </option>
    <option value="Other Healthcare Professional">
        Other Healthcare Professional
    </option>
            </select>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required style="border: 1px solid #077c89; background-color: #eefdff;">
            <button type="submit" class="btn-med w-100" style="border: none; border-radius: 12px; padding: 10px 24px; margin-top: 20px; background-color: #056873; color: white; font-size: 18px; ">Sign Up</button>
        </form>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>


