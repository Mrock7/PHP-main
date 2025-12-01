<?php
$age = 15;

switch($age){
    case($age >= 0 && $age < 18):
    echo "you are a minor <br>";
    break;

        case($age >= 18 && $age <= 20):
    echo "you are a younger adult <br>";
    break;

        case($age > 25):
    echo "you are a adult <br>";
    break;
    default:
    echo "invalid input <br>";
    break;
}
?>