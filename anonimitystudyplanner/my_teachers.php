<?php
session_start();
require_once('config.php');
if (!isset($_SESSION['id']) || $_SESSION['role']!=='student') header("Location: home.php");
$student_id = $_SESSION['id'];
$stmt = $conn->prepare("
SELECT u.id AS teacher_id,u.user,u.teaching,SUM(a.is_read=0) AS unread
FROM teacher_student ts
JOIN users u ON ts.teacher_id=u.id
LEFT JOIN assignments a ON a.teacher_id=u.id AND a.student_id=:sid
GROUP BY u.id
");
$stmt->execute(['sid'=>$student_id]);
$teachers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Teachers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--primary-color:#8f1a1aff;--primary-hover:#751515}body{font-family:'Segoe UI',sans-serif;background:#f5f5f5}.container{max-width:600px;margin-top:50px}h2{color:var(--primary-color);font-weight:bold}.card{border-left:4px solid var(--primary-color);transition:transform 0.2s;cursor:pointer}.card:hover{transform:scale(1.02);background:#f0f0f0}.subject{font-style:italic;color:#555}.badge{background-color:var(--primary-color)}
</style>
</head>
<body>
<div class="container">
<h2 class="mb-4 text-center">Your Teachers</h2>
<?php if(count($teachers)===0):?>
<div class="alert alert-info">You have no teachers assigned.</div>
<?php else:?>
<input type="text" id="search" class="form-control mb-3" placeholder="Search teachers or subjects...">
<div id="teacherList">
<?php foreach($teachers as $teacher):?>
<div class="card mb-2 p-3">
<strong class="teacher-name"><?=htmlspecialchars($teacher['user'])?></strong><br>
<span class="subject"><?=htmlspecialchars($teacher['teaching'])?></span>
<?php if($teacher['unread']>0):?><span class="badge rounded-pill"><?=$teacher['unread']?></span><?php endif;?>
</div>
<?php endforeach;?>
</div>
<?php endif;?>
<button class="btn btn-secondary mt-3" onclick="history.back()">Go Back</button>
</div>
<script>
const searchInput=document.getElementById('search');
const teacherCards=document.querySelectorAll('.teacher-card');
searchInput.addEventListener('input',function(){
    const term=this.value.toLowerCase();
    teacherCards.forEach(card=>{
        const name=card.querySelector('.teacher-name').textContent.toLowerCase();
        const subject=card.querySelector('.subject').textContent.toLowerCase();
        card.style.display=(name.includes(term)||subject.includes(term))?'block':'none';
    });
});
</script>
</body>
</html>