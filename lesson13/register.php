<?php

include("config.php");


if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $tempPass = $_POST['password'];


    $password = $tempPass;

    }

    if(empty($name) || empty($surname) || empty($email) || empty($tempPass)) {
        echo "All fields are required.";
    } else {
        $sql = "SELECT username FROM users WHERE username=:username'";
        $tempSQL = $conn->prepare($sql);
        $tempSQL->bindParam(':username', $username);
        $tempSQL->execute();
        if ($tempSQL->rowCount() > 0) {
            echo "Username already exists. Please choose another one.";
            header("refresh:0;url=signup.php");
        }else{
            $sql= "INSERT INTO users (name, surname, username, email, password) VALUES (:name, :surname, :username, :email, :password)";
            $insertsql = $conn->prepare($sql);
            $insertsql->bindParam(':name', $name);
            $insertsql->bindParam(':surname', $surname);
            $insertsql->bindParam(':username', $username);
            $insertsql->bindParam(':email', $email);
            $insertsql->bindParam(':password', $password);
            $insertsql->execute();
                echo "Registration successful. ";
                header("refresh:2;url=login.php");
        }
} 


?>
