<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "college_result_display";

$conn = mysqli_connect("localhost","root","","college_result_display");

if($conn->connect_error)
    {
        echo "Failed to Connect Database" .$conn->connect_error;
    }
?>