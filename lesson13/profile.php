<?php

session_start();    

include_once("config.php");

if(empty($_SESSION['username']))
     {
    header("Location: login.php");
}
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id='$id'";
$selectuser = $conn->prepare($sql);
$selectuser->bindParam(':id', $id); 
$selectuser->execute();

$user_data = $selectuser->fetch();

?>