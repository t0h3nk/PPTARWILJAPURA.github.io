<?php
session_start();
include '../config/database.php';

// Jika admin sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] == true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek kecocokan username dan password di database
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Jika cocok, buat sesi login dan arahkan ke dashboard
        $_SESSION['status_login'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Ponpes Darussalam</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #2e7d32; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); width: 100%; max-width: 350px; text-align: center; }
        .login-box h2 { color: #1b5e20; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-login { background-color: #f57f17; color: white; border: none; padding: 10px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1rem; }
        .btn-login:hover { background-color: #f9a825; }
        .error-msg { color: red; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Admin Pesantren</h2>
        <?php if($error != "") echo "<div class='error-msg'>$error</div>"; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Masuk Dashboard</button>
        </form>
    </div>

</body>
</html>