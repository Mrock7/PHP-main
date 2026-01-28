<?php

session_start();

require 'config.php';


if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    if (empty($username) || empty($password)) {
        echo "Both fields are required.";
        header("refresh:2; url=login.php");
    } else {
               $sql = "SELECT username FROM users WHERE username=:username'";
        $insertsql = $conn->prepare($sql);
        $insertsql->bindParam(':username', $username);
        $insertsql->execute();
        if ($insertsql->rowCount() > 0) {
            $data = $insertsql->fetch();

            if ($password == $data['password']) {
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
            }  exit();
        } else {
            echo "Invalid username or password.";
            header("refresh:2; url=login.php");
            exit();
        }else{
            echo "USERNAME DOES NOT EXIST";
            header("refresh:2; url=login.php");
            exit();
   }
    }   

}

?>


