<?php



function fully_divisible($n){
    if(($n % 2) ==0 ){
        return "$n is fully divisible by 2";
    
    }
    else{
        return "$n is not  fully divisible by 2";
    }



}
print_r(fully_divisible(4)."<br>");
print_r(fully_divisible(35)."<br>");
print_r(fully_divisible(15)."<br>");
print_r(fully_divisible(16)."<br>");


?>