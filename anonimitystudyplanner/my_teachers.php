<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'student') {
    header("Location: home.php");
    exit;
}

$student_id = $_SESSION['id'];

$stmt = $conn->prepare("
    SELECT u.id AS teacher_id, u.user AS teacher_name, u.teaching,
        SUM(a.is_read = 0) AS unread
    FROM teacher_student ts
    JOIN users u ON ts.teacher_id = u.id
    LEFT JOIN assignments a ON a.teacher_id = u.id AND a.student_id = :sid
    GROUP BY u.id
");
$stmt->execute(['sid' => $student_id]);
$teachers = $stmt->fetchAll();

$stmt2 = $conn->prepare("
    SELECT a.*, u.user AS teacher_name
    FROM assignments a
    JOIN users u ON a.teacher_id = u.id
    WHERE a.student_id = :sid
    ORDER BY a.due_date ASC
");
$stmt2->execute(['sid' => $student_id]);
$all_assignments = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Teachers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--primary-color:#8f1a1aff;--primary-hover:#751515}
body{font-family:'Segoe UI',sans-serif;background:#f5f5f5}
.container{max-width:600px;margin-top:50px}
h2{color:var(--primary-color);font-weight:bold}
.card{border-left:4px solid var(--primary-color);transition:transform 0.2s;cursor:pointer}
.card:hover{transform:scale(1.02);background:#f0f0f0}
.subject{font-style:italic;color:#555}
.badge{background-color:var(--primary-color);cursor:pointer}
#assignmentsBtn{position:fixed;bottom:20px;right:20px;z-index:999;}
</style>
</head>
<body>
<div class="container">
<h2 class="mb-4 text-center">Your Teachers</h2>

<?php if(count($teachers) === 0): ?>
<div class="alert alert-info">You have no teachers assigned.</div>
<?php else: ?>
<input type="text" id="search" class="form-control mb-3" placeholder="Search teachers or subjects...">
<div id="teacherList">
<?php foreach($teachers as $teacher): ?>
<div class="card mb-2 p-3">
<strong class="teacher-name"><?=htmlspecialchars($teacher['teacher_name'])?></strong><br>
<span class="subject"><?=htmlspecialchars($teacher['teaching'])?></span>
<?php if($teacher['unread'] > 0): ?>
<span class="badge rounded-pill" data-teacher="<?= $teacher['teacher_id'] ?>" onclick="showTeacherAssignments(this)"><?= $teacher['unread'] ?></span>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>

<button class="btn btn-danger" id="assignmentsBtn" onclick="showAllAssignments()">My Assignments</button>

<div class="modal fade" id="assignmentsModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Assignments</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body" id="assignmentsBody"></div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const allAssignments = <?= json_encode($all_assignments) ?>;

const searchInput = document.getElementById('search');
searchInput.addEventListener('input', function(){
    const term = this.value.toLowerCase();
    document.querySelectorAll('#teacherList .card').forEach(card=>{
        const name = card.querySelector('.teacher-name').textContent.toLowerCase();
        const subject = card.querySelector('.subject').textContent.toLowerCase();
        card.style.display = (name.includes(term) || subject.includes(term)) ? 'block' : 'none';
    });
});

function showTeacherAssignments(badge){
    const teacherId = badge.dataset.teacher;
    const container = document.getElementById('assignmentsBody');
    const filtered = allAssignments.filter(a => a.teacher_id == teacherId);
    let html = '';
    if(filtered.length === 0) html = '<p>No assignments from this teacher.</p>';
    else{
        html = '<ul class="list-group">';
        filtered.forEach(a=>{
            html += `<li class="list-group-item">
                <strong>${a.title}</strong><br>
                Type: ${a.type} | Due: ${a.due_date}<br>
                ${a.instructions}
            </li>`;
        });
        html += '</ul>';
    }
    container.innerHTML = html;
    badge.remove();
    const modal = new bootstrap.Modal(document.getElementById('assignmentsModal'));
    modal.show();
    fetch('mark_read.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'teacher_id=' + encodeURIComponent(teacherId)
    }).then(res => res.json());
}

function showAllAssignments(){
    const container = document.getElementById('assignmentsBody');
    let html = '';
    if(allAssignments.length === 0) html = '<p>You have no assignments.</p>';
    else{
        html = '<ul class="list-group">';
        allAssignments.forEach(a=>{
            html += `<li class="list-group-item">
                <strong>${a.title}</strong> | Teacher: ${a.teacher_name}<br>
                Type: ${a.type} | Due: ${a.due_date}<br>
                ${a.instructions}
            </li>`;
        });
        html += '</ul>';
    }
    container.innerHTML = html;
    const modal = new bootstrap.Modal(document.getElementById('assignmentsModal'));
    modal.show();
}
</script>
</body>
</html>