<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: home.php");
    exit;
}

$teacher_id = $_SESSION['id'];

/* Get students */
$stmt = $conn->prepare("SELECT id, user FROM users WHERE role = 'student'");
$stmt->execute();
$students = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_id = intval($_POST['student_id']);

    $insert = $conn->prepare("
        INSERT IGNORE INTO teacher_student (teacher_id, student_id)
        VALUES (:tid, :sid)
    ");

    $insert->execute([
        'tid' => $teacher_id,
        'sid' => $student_id
    ]);

    header("Location: home.php");
    exit;
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