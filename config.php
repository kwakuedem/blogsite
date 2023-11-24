<?php

$server="localhost";
$username="root";
$password="";
$db="blog_db";

$conn=new mysqli($server,$username,$password,$db);

if($conn->connect_error){
    die("Database Connection Failed" .$conn->connect_error);
}

?>