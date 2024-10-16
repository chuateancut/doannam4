<?php 

$sever = "localhost";
$username = "root";
$pass = "";
$database = "thongtinshopdnc";
$conn = new mysqli($sever,$username,$pass,$database);
if ($conn -> connect_error){
    die("kết nối thất bại   ".$conn -> connect_error);
}else{
    echo "";
}
?>
