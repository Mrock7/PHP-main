<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

if ($role !== 'student' && $role !== 'teacher') {
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($role === 'teacher') {
    $stmt = $conn->prepare("
        SELECT u.id, u.user, u.grade
        FROM users u
        JOIN teacher_student ts ON u.id = ts.student_id
        WHERE ts.teacher_id = :tid
    ");
    $stmt->execute(['tid' => $_SESSION['id']]);
} else {
    $stmt = $conn->prepare("SELECT id, user, grade FROM users WHERE role = 'student'");
    $stmt->execute();
}

$students_data = $stmt->fetchAll();

$stmt2 = $conn->prepare("SELECT COUNT(*) AS total_teachers FROM users WHERE role = 'teacher'");
$stmt2->execute();
$teacher_count = $stmt2->fetch()['total_teachers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Anonimity Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tomorrow:wght@400;700&display=swap" rel="stylesheet">

<style>
body { 
    font-family: 'Tomorrow', sans-serif; 
    background-color: #f8f9fa; 
}

.navbar-custom { 
    background-color: #8f1a1aff; 
}

.navbar-custom .navbar-brand, 
.navbar-custom .nav-link { 
    color: white; 
}

.table-container { 
    margin: 50px auto; 
    max-width: 1100px; 
}

h2 { 
    color: #8f1a1aff; 
    margin-bottom: 30px; 
}

.my-red-btn {
  background-color: #8f1a1aff;
  color: white;
  margin-bottom: 20px;
}

.my-red-btn:hover {
  background-color: #751515;
  color: white;
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
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php if($role === 'student'): ?>
          <li class="nav-item"><a class="nav-link" href="my_teachers.php">Your Teachers</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container table-container">

    <?php if($role === 'teacher'): ?>
        <div class="mb-3">
            <a href="assign_student.php" class="btn my-red-btn me-2">Assign Student</a>
            <a href="edit_students.php" class="btn my-red-btn">Edit Students</a>
        </div>
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