<?php
include("db.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Exam Result</title>
    <link rel="stylesheet" href="/student-result-system/style.css">
    <style>
        h2 
        {
            text-align: center;
        }
    </style>
</head>
<body>
<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">
<center>
<div class="container">
<form method="POST" action="update_process.php">
    <h2>UPDATE STUDENT DATA</h2>
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

Name: <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
Roll No: <input type="text" name="rollno" value="<?php echo $row['rollno']; ?>"><br>
Email: <input type="email" name="email" value="<?php echo $row['email']; ?>"><br>
<button type="submit">Update</button>
</form>
</div>
</center>
</body>