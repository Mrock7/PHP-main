<?php
include_once('config.php');
session_start();

// Fetch all students for display
$sql = "SELECT Student, grade FROM students";
$selectStudents = $conn->prepare($sql);
$selectStudents->execute();
$students_data = $selectStudents->fetchAll();

// Count teachers (users with is_admin = 1)
$sql2 = "SELECT COUNT(*) AS total_teachers FROM users WHERE is_admin = 1";
$selectTeachers = $conn->prepare($sql2);
$selectTeachers->execute();
$teacher_count = $selectTeachers->fetch(PDO::FETCH_ASSOC)['total_teachers'];
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
        <li class="nav-item"><a class="nav-link" href="login.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container table-container">
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
                <?php foreach($students_data as $student) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['Student']); ?></td>
                        <td><?php echo htmlspecialchars($student['grade']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <p class="mt-4">Total Teachers: <?php echo $teacher_count; ?></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
