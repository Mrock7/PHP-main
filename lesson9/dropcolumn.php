<?php

try{
    $pdo = new PDO("mysql:host=localhost;dbname=testdb",  "root",  "");

    $sql = "ALTER TABLE products DROP COLUMN email";

    $pdo->exec($sql);


    echo "column dropped successfully!";

}catch (PDOException $e){
    echo  $e->getMessage();
}



?>