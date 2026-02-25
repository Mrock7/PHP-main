<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: home.php");
    exit;
}

$student_id = $_SESSION['id'];

$sql = "SELECT u.user 
        FROM users u
        JOIN student_teacher st ON u.id = st.teacher_id
        WHERE st.student_id = :sid";
$stmt = $conn->prepare($sql);
$stmt->execute(['sid'=>$student_id]);
$teachers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Teachers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Your Teachers</h2>
<?php if(count($teachers) === 0): ?>
    <p>You have no teachers assigned.</p>
<?php else: ?>
    <ul>
    <?php foreach($teachers as $teacher): ?>
        <li><?= htmlspecialchars($teacher['user']) ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>