<?php
session_start();
require_once('config.php');

$error = "";

if (isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM users WHERE user = :user";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user' => $username]);
        $userData = $stmt->fetch();

        if ($userData && password_verify($password, $userData['password'])) {

            $_SESSION['user'] = $userData['user'];
            $_SESSION['role'] = $userData['role'];
            $_SESSION['grade'] = $userData['grade'] ?? null;
            $_SESSION['id'] = $userData['id'];

            header("Location: home.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tomorrow:wght@400;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Tomorrow', sans-serif; background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
.form-signin { width: 100%; max-width: 400px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1); }
.custom-red { background-color: #8f1a1aff; border-color: #8f1a1aff; color: white; }
.custom-red:hover { background-color: #751515; border-color: #751515; }
.title-red { color: #8f1a1aff; font-weight: bold; text-align: center; }
</style>
</head>
<body>

<main class="form-signin">
<form method="post" action="">
    <h1 class="h3 mb-3 fw-normal title-red">Welcome Back</h1>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="form-floating mb-2">
        <input type="text" class="form-control" placeholder="Username" name="username" required>
        <label>Username</label>
    </div>

    <div class="form-floating mb-3">
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <label>Password</label>
    </div>

    <button class="w-100 btn btn-lg custom-red" type="submit" name="submit">Login</button>
    <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Sign up</a></p>
</form>
</main>

</body>
</html>
