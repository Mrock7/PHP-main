<?php
include_once('config.php');
session_start();

$user = $_POST['username'];
$role = $_POST['role'];
$grade = $_POST['grade'] ?? null;


if($role === 'student') {
    $sql = "INSERT INTO students (Student, grade) VALUES (:Student, :grade)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['Student' => $user, 'grade' => $grade]);
} elseif($role === 'teacher') {
    $sql = "INSERT INTO users (user, is_admin) VALUES (:user, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user' => $user]);
}


$_SESSION['user'] = $user;
$_SESSION['role'] = $role;
$_SESSION['is_admin'] = ($role === 'teacher') ? true : false;

header("Location: home.php");
exit;
