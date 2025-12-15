<?php


$myfilename="ds.txt";


$myfile=fopen($myfilename,'r');


$my_size=filesize($myfilename);


$my_filedata=fread($myfile,$my_size,);


?>