<?php
function my_connectDB(){
    $host = "localhost";
    $user = "root";
    $pwd  = "";
    $db   = "AsiAStore";



    $conn = mysqli_connect($host, $user, $pwd, $db) or die("Error connect to database");


    return $conn;
}
function my_closeDB($conn)
{
    mysqli_close($conn);
}
?>