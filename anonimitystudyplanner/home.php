<?php
session_start();
require_once('config.php');


if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}


$role = $_SESSION['role'] ?? null;
if ($role !== 'student' && $role !== 'teacher') {
    session_destroy();
    header("Location: login.php");
    exit;
}


$sql = "SELECT id, user, grade FROM users WHERE role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$students_data = $stmt->fetchAll();

$sql2 = "SELECT COUNT(*) AS total_teachers FROM users WHERE role = 'teacher'";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$teacher_count = $stmt2->fetch()['total_teachers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Anonimity Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tomorrow:wght@400;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Tomorrow', sans-serif; background-color: #f8f9fa; }
.navbar-custom { background-color: #8f1a1aff; }
.navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white; }
.table-container { margin-top: 50px; }
h2 { color: #8f1a1aff; margin-bottom: 30px; }

.my-red-btn {
  background-color: #8f1a1aff;
  color: white;
  margin-bottom: 20px;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Anonimity</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php if($_SESSION['role'] === 'student'): ?>
          <li class="nav-item"><a class="nav-link" href="my_teachers.php">Your Teachers</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container table-container">
    <?php if($_SESSION['role'] === 'teacher'): ?>
  
        <a href="assign_student.php" class="btn my-red-btn">Assign Student</a>
    <?php endif; ?>

    <h2>Students List</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Student</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students_data as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['user']) ?></td>
                        <td><?= htmlspecialchars($student['grade']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="mt-4">Total Teachers: <?= $teacher_count ?></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>