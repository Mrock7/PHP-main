<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['id'];

$stmt = $conn->prepare("
SELECT u.id, u.user 
FROM users u 
JOIN teacher_student ts ON u.id = ts.student_id 
WHERE ts.teacher_id = :tid
");
$stmt->execute(['tid' => $teacher_id]);
$students = $stmt->fetchAll();

if (isset($_GET['delete_student'])) {
    $student_id = intval($_GET['delete_student']);


    $conn->prepare("DELETE FROM assignments WHERE teacher_id = :tid AND student_id = :sid")->execute([
        'tid' => $teacher_id,
        'sid' => $student_id
    ]);

    $conn->prepare("DELETE FROM teacher_student WHERE teacher_id = :tid AND student_id = :sid")->execute([
        'tid' => $teacher_id,
        'sid' => $student_id
    ]);

    header("Location: edit_students.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_assignment'])) {
    $student_id = intval($_POST['student_id']);
    $type = $_POST['type'];
    $title = trim($_POST['title']);
    $instructions = trim($_POST['instructions']);
    $word_count = ($type === 'paper') ? intval($_POST['word_count']) : null;
    $due_date = $_POST['due_date'];

    $check = $conn->prepare("SELECT 1 FROM teacher_student WHERE teacher_id = :tid AND student_id = :sid");
    $check->execute(['tid' => $teacher_id, 'sid' => $student_id]);

    if ($check->fetch()) {
        $conn->prepare("
            INSERT INTO assignments 
            (teacher_id, student_id, type, title, instructions, word_count, due_date) 
            VALUES (:tid,:sid,:type,:title,:instructions,:word_count,:due_date)
        ")->execute([
            'tid' => $teacher_id,
            'sid' => $student_id,
            'type' => $type,
            'title' => $title,
            'instructions' => $instructions,
            'word_count' => $word_count,
            'due_date' => $due_date
        ]);
    }

    header("Location: edit_students.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Students</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--primary-color:#8f1a1aff;--primary-hover:#751515}body{font-family:'Segoe UI',sans-serif;background:#f5f5f5}.container{max-width:700px;margin-top:50px}h2{color:var(--primary-color);font-weight:bold}.btn-primary,.btn-success,.btn-danger,.btn-secondary{background-color:var(--primary-color);border-color:var(--primary-color);color:#fff}.btn-primary:hover,.btn-success:hover,.btn-danger:hover,.btn-secondary:hover{background-color:var(--primary-hover);border-color:var(--primary-hover)}.card{border-left:4px solid var(--primary-color)}.badge{background-color:var(--primary-color)}
</style>
</head>
<body>
<div class="container">
<h2 class="mb-4 text-center">Your Students</h2>
<?php foreach($students as $student): ?>
<div class="card mb-3 p-3">
<strong><?=htmlspecialchars($student['user'])?></strong>
<div class="mt-2">
<a href="edit_students.php?delete_student=<?=$student['id']?>" class="btn btn-danger btn-sm">Delete</a>
<button class="btn btn-success btn-sm" data-bs-toggle="collapse" data-bs-target="#assignForm<?=$student['id']?>">Add Assignment</button>
</div>
<div class="collapse mt-3" id="assignForm<?=$student['id']?>">
<form method="post">
<input type="hidden" name="student_id" value="<?=$student['id']?>">
<input type="hidden" name="add_assignment" value="1">
<div class="mb-2"><label>Title:</label><input type="text" name="title" class="form-control" required></div>
<div class="mb-2"><label>Type:</label><select name="type" class="form-select" onchange="toggleWordCount(this,<?=$student['id']?>)" required><option value="test">Test</option><option value="paper">Paper</option></select></div>
<div class="mb-2" id="wordCountDiv<?=$student['id']?>" style="display:none;"><label>Word Count:</label><input type="number" name="word_count" class="form-control"></div>
<div class="mb-2"><label>Instructions / Description:</label><textarea name="instructions" class="form-control" rows="3" required></textarea></div>
<div class="mb-2"><label>Due Date:</label><input type="date" name="due_date" class="form-control" required></div>
<button type="submit" class="btn btn-primary btn-sm">Add Assignment</button>
</form>
</div>
</div>
<?php endforeach; ?>
<button class="btn btn-secondary mt-3" onclick="history.back()">Go Back</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleWordCount(select, studentId){
    document.getElementById('wordCountDiv'+studentId).style.display=(select.value==='paper')?'block':'none';
}
</script>
</body>
</html>