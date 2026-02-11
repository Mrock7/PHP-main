<?php
include_once('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $role = $_POST['role'];
    $grade = $_POST['grade'] ?? null;

    if ($role === 'student') {
        $sql = "INSERT INTO students (Student, grade) VALUES (:Student, :grade)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['Student' => $user, 'grade' => $grade]);
    } elseif ($role === 'teacher') {
        $sql = "INSERT INTO users (user, is_admin) VALUES (:user, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user' => $user]);
    }

    $_SESSION['user'] = $user;
    $_SESSION['role'] = $role;
    $_SESSION['is_admin'] = ($role === 'teacher') ? true : false;

    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
  <form method="post">
    <h1 class="h3 mb-3 fw-normal" style="color:#8f1a1aff;">Register Now</h1>

    <div class="form-floating mb-2">
      <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" required>
      <label for="floatingInput">Username</label>
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

    <button class="w-100 btn btn-lg" type="submit" style="background-color:#8f1a1aff;color:white;">Sign Up</button>
  </form>
</main>

<script>
document.getElementById('role').addEventListener('change', function() {
    document.getElementById('grade').style.display = (this.value === 'student') ? 'block' : 'none';
});
window.onload = function() {
    document.getElementById('grade').style.display = 'none';
}
</script>

</body>
</html>
