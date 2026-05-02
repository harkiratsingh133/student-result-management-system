<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Exam Result</title>
    <link rel="stylesheet" href="/student-result-system/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
     .showresult
     {
        font-weight: bold;
        font-family: calibri;
        font-size: x-large;
     }
     .showresult i 
     {
        font-size: 35px;
        position: relative;
        right: 12px;
     }
     .fa-id-card 
     {
        font-size: 35px;
        color: navy;
     }
     .fa-layer-group 
     {
        font-size: 35px;
        color: navy;
     }
     .error-msg
     {
        width:300px;
        margin:15px auto;
        padding:10px;
        background:#dc3545;
        color:white;
        text-align:center;
        border-radius:8px;
        font-weight:bold;
     }

.error-msg i{
margin-right:6px;
}
    </style>
</head>
<body>

    <h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
    <hr class="main-line">

    <div class="container">

        <h1>WELCOME TO STUDENT RESULT MANAGEMENT SYSTEM</h1>
        <h3>VIEW RESULT</h3>
        <?php if(isset($_GET['msg']) && $_GET['msg']=="norecord"){ ?>
    
<div class="error-msg" id="msgBox">
<i class="fa-solid fa-circle-xmark"></i> No Records Found
</div>

<script>
setTimeout(function(){
document.getElementById("msgBox").style.display="none";
},3000);
</script>

<?php } ?>
        <div class="form-box">
            
            <form action="result.php" method="GET">
                <label><i class="fa fa-id-card"></i> ROLL NUMBER</label>
                <input type="text" name="rollno" placeholder="Enter your Roll Number" required>
                <label><i class="fa fa-layer-group"></i> SEMESTER</label>
                <input type="text" name="sem" placeholder="Enter Semester" required>
                <button type="submit" class="showresult"><i class="fa-solid fa-chart-bar"></i>SHOW</button>
            </form>
        </div>

        <div class="links">
            <a href="student_register.php">STUDENT REGISTER</a>
            <a href="faculty_login.php">FACULTY LOGIN</a>
            <a href="faculty_register.php">FACULTY REGISTRATION</a>
            <a href="admin_login.php">ADMIN LOGIN</a>
        </div>

    </div>

</body>
</html>