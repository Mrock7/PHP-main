<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: home.php");
    exit;
}


$sql = "SELECT id, user FROM users WHERE role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);
    $teacher_id = $_SESSION['id'];


    $check = $conn->prepare("SELECT * FROM student_teacher WHERE student_id = :sid AND teacher_id = :tid");
    $check->execute(['sid'=>$student_id, 'tid'=>$teacher_id]);
    if (!$check->fetch()) {
        $insert = $conn->prepare("INSERT INTO student_teacher (student_id, teacher_id) VALUES (:sid, :tid)");
        $insert->execute(['sid'=>$student_id, 'tid'=>$teacher_id]);
    }

    header("Location: home.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Assign Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Assign a Student</h2>
<form method="post">
    <select name="student_id" class="form-select mb-3" required>
        <?php foreach($students as $student): ?>
        <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['user']) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-danger">Assign</button>
</form>
</body>
</html>