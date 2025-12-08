<?php
function callcounter(){
    static $count=2;
    $count++;
    echo "the value is $count <br>";
}
callcounter();
callcounter();
callcounter();
callcounter();

?>