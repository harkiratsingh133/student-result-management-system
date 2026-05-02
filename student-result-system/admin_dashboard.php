<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$username = $_SESSION['admin'];

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>
<link rel="stylesheet" href="/student-result-system/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.container
{
width:1000px;
margin:auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
position: relative;
top: 20px;
}
.container:hover 
{
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}
.links{
margin-top:25px;
}

.links a{
display:flex;
align-items:center;
justify-content:center;
gap:10px;
padding:14px;
margin:12px 0;
background:#007bff;
color:white;
text-decoration:none;
border-radius:10px;
text-align:center;
font-weight:bold;
font-size:20px;
transition:0.3s;
}

.links a:hover{
background:#000080;
transform:scale(1.03);
}

.logout-link{
text-align:center;
margin-top:20px;
}

.logout-link a{
background:#dc3545;
color:white;
padding:10px 20px;
text-decoration:none;
border-radius:8px;
font-weight:bold;
font-size:16px;
}

.logout-link a:hover{
background:#000080;
}

.icon{
font-size:22px;
}

.fa-gauge{
font-size:40px;
color:navy;
margin-bottom:10px;
}

</style>

</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr>

<div class="container" style="text-align:center;">

<i class="fa-solid fa-gauge"></i>

<h2>Admin Dashboard</h2>
<h3>Welcome, <?php echo ucwords(strtolower($username)); ?> 👋</h3>

<p>Select a table to manage</p>

<div class="links">

<a href="admin_view.php?table=faculty">
<i class="fa fa-chalkboard-teacher icon"></i> VIEW FACULTY
</a>

<a href="admin_view.php?table=students">
<i class="fa fa-user-graduate icon"></i> VIEW STUDENTS
</a>

<a href="admin_view.php?table=subjects">
<i class="fa fa-book icon"></i> VIEW SUBJECTS
</a>

<a href="admin_view.php?table=marks">
<i class="fa fa-table icon"></i> VIEW MARKS (Topper & Rank)
</a>

<a href="rank_view.php">
        <i class="fa-solid fa-ranking-star"></i> VIEW RANK LIST
</a>
</div>

<div class="logout-link">
<a href="admin_dashboard.php?logout=true">
<i class="fa fa-sign-out-alt"></i> LOGOUT
</a>
</div>

</div>

</body>
</html>