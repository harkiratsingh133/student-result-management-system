<?php
session_start();
include("db.php");

if(!isset($_SESSION['email']))
{
    header("Location: index.php");
    exit();
}

// ✅ SAFE ACCESS (no warnings)
$name = $_SESSION['name'] ?? '';
$sem = $_SESSION['sem'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Homepage</title>
    <link rel="stylesheet" type="text/css" href="/student-result-system/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    .btn 
    {
        text-decoration: none;
        text-transform: uppercase;
    }
    .main-content 
    {
    width: 800px;
    height: 600px;
    background: rgba(255, 255, 255, 1);
    border-radius: 15px;
    text-align: center;
    }
    .main-content:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}
    .welcome
    {
        font-size: xx-large;
        position: relative;
        top: 20px;
    }
    .btn 
    {
        position: relative;
        margin: auto;
        font-size: xx-large;
    }
    .fa-sign-out-alt 
    {
        position: relative;
        right: 2px;
    }
    .fa-key
    {
        position: relative;
        right: 3px;
    }
    .fa-user-graduate 
    {
        position: relative;
        right: 3px;
    }
    .fa-book-medical 
    {
        position: relative;
        right: 5px;
    }
    .fa-paper-plane 
    {
        position: relative;
        right: 5px;
    }
    </style>
</head>
<body>
<br>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Home</title>
    <link rel="stylesheet" href="/student-result-system/style.css">
</head>
<body>
<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">
<center>
<div class="main-content">
    <h2 class="Welcome">WELCOME <?php echo $name; ?></h2>

<p><strong>Name:</strong> <?php echo $name; ?></p>
<p><strong>Semester:</strong> <?php echo $sem; ?></p>

    <hr>

    <div class="links">
        <a href="viewusers.php" class="btn"><i class="fa-solid fa-user-graduate"></i>VIEW REGISTERED STUDENTS</a><br><br>

        <a href="viewmarks.php" class="btn"><i class="fa-solid fa-chart-column"></i> VIEW STUDENTS MARKS</a><br><br>

        <a href="addsubjects.php" class="btn"><i class="fa-solid fa-book-medical"></i>ADD SUBJECTS</a><br><br>

        <a href="submitmarks.php" class="btn"><i class="fa-solid fa-paper-plane"></i>SUBMIT MARKS</a><br><br>

        <a href="changepassword.php" class="btn"><i class="fa-solid fa-key"></i>CHANGE PASSWORD</a><br><br>

        <a href="faculty_logout.php" class="btn"><i class="fa fa-sign-out-alt"></i>LOGOUT</a>
    </div>
</div>
</center>
</body>
</html>