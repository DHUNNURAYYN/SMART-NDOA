<?php
$server = 'localhost';
$user = 'root';
$password ='ochu1234';
$db_name = 'smart_ndoa';

$conn = mysqli_connect($server,$user,$password,$db_name);
if (!$conn){
    echo"connection fail";
}
?>


 