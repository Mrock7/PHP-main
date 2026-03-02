<?php
session_start();
require_once('config.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$username = trim($_POST['username']);
$password = $_POST['password'];
$role = $_POST['role'];
$grade = $_POST['grade'] ?? null;
$teaching = $_POST['teaching'] ?? null;

    if (empty($username) || empty($password) || empty($role)) {
        $error = "Please fill in all required fields.";
    } else {
        $checkSql = "SELECT * FROM users WHERE user = :user";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->execute(['user' => $username]);

        if ($stmtCheck->fetch()) {
            $error = "Username already taken.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (user, password, role, grade, teaching) 
        VALUES (:user, :password, :role, :grade, :teaching)";

        $stmt = $conn->prepare($sql);

         $stmt->execute([
    'user' => $username,
    'password' => $hashedPassword,
    'role' => $role,
    'grade' => ($role === 'student') ? $grade : null,
    'teaching' => ($role === 'teacher') ? $teaching : null
]);



            $_SESSION['user'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['grade'] = ($role === 'student') ? $grade : null;
            $_SESSION['id'] = $conn->lastInsertId();

            header("Location: home.php");
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
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
<form method="post">
    <h1 class="h3 mb-3 fw-normal title-red">Register Now</h1>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="form-floating mb-2">
        <input type="text" class="form-control" placeholder="Username" name="username" required>
        <label>Username</label>
    </div>

    <div class="form-floating mb-2">
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <label>Password</label>
    </div>

    <select class="form-select mb-2" name="role" id="role" required>
        <option value="" selected>Choose Role</option>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
    </select>

    <select class="form-select mb-3" name="grade" id="grade">
        <option value="" selected>Choose Grade (for students)</option>
        <option value="6">Grade 6</option>
        <option value="7">Grade 7</option>
        <option value="8">Grade 8</option>
        <option value="9">Grade 9</option>
        <option value="10">Grade 10</option>
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
    </select>


    <select class="form-select mb-3" name="teaching" id="teaching">
    <option value="" selected>What subject do you teach?</option>
    <option value="Math">Math</option>
    <option value="English">English</option>
    <option value="Science">Science</option>
    <option value="History">History</option>
</select>


    <button class="w-100 btn btn-lg custom-red" type="submit">Sign Up</button>
    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
</form>
</main>


<script>
const roleSelect = document.getElementById('role');
const gradeSelect = document.getElementById('grade');
const teachingSelect = document.getElementById('teaching');

function toggleFields() {
    if (roleSelect.value === 'student') {
        gradeSelect.style.display = 'block';
        teachingSelect.style.display = 'none';
    } 
    else if (roleSelect.value === 'teacher') {
        gradeSelect.style.display = 'none';
        teachingSelect.style.display = 'block';
    } 
    else {
        gradeSelect.style.display = 'none';
        teachingSelect.style.display = 'none';
    }
}

roleSelect.addEventListener('change', toggleFields);
window.onload = toggleFields;
</script>


</body>
</html>
