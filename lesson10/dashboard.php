<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
     include_once('config.php');
     $sql = "SELECT * FROM users";
     $getUsers = $conn->prepare($sql);
     $getUsers->execute();
     $users = $getUsers->fetchAll();    
    ?>
</body>
</html>