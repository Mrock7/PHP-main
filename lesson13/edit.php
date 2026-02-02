<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    form>input {
        margin-bottom:10px;
        font-size:100px;
        padding:5px;
    }

    button{
        padding:10px 40px;
        font-size:20px;
        cursor:pointer;
        border:1px solid black;
    }
    </style>
</head>
<body>
    <form action="update.php" method="POST">

    <input type="hidden" name="id" value="<?= $user['id']; ?>">
    <input type="text" name="name" value="<?= $user['name']; ?>">
    <input type="text" name="surname" value="<?= $user['surname']; ?>">
    <input type="email" name="email" value="<?= $user['email']; ?>">
    
    <br><br>
    <button type="submit" name="name" >Update User</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>