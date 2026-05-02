<?php
session_start();
include("db.php");

$message="";
$type="";

if(isset($_POST['login']))
{
    $username=$_POST['username'];
    $password=$_POST['password'];

    // check if more than one admin exists
    $countQuery="SELECT * FROM admin";
    $countResult=mysqli_query($conn,$countQuery);

    if(mysqli_num_rows($countResult)>1)
    {
        $message="Only One Admin Allowed";
        $type="error";
    }
    else
    {
        $sql = "SELECT * FROM admin 
        WHERE username='$username' 
        AND password=SHA2('$password',256)";
        $result=mysqli_query($conn,$sql);

        if(mysqli_num_rows($result)==1)
        {
            $_SESSION['admin']=$username;

            $message="Admin Login Successfully Done";
            $type="success";

            echo "<script>
            setTimeout(function(){
            window.location.href='admin_dashboard.php';
            },2000);
            </script>";
        }
        else
        {
            $message="Invalid Username or Password";
            $type="error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Login</title>
<link rel="stylesheet" href="/student-result-system/admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.adminlogin
{
width:1000px;
margin:auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}
.adminlogin:hover
{
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}
.msg{
padding:12px;
border-radius:8px;
color:white;
font-weight:bold;
margin-top:15px;
text-align:center;
animation:fadeOut 3s forwards;
}
.msg i{
margin-right:6px;
}
.success{background:#28a745;}
.error{background:#dc3545;}

@keyframes fadeOut{
0%{opacity:1;}
80%{opacity:1;}
100%{opacity:0;}
}

.back-link{
margin-top:20px;
text-align:center;
}

.back-link a{
display:inline-block;
padding:10px 18px;
background:#6c757d;
color:white;
text-decoration:none;
border-radius:6px;
font-weight:bold;
transition:0.3s;
}

.back-link a:hover{
background:#000080;
}
.fa-user-shield
{
    font-size: 35px;
    color: navy;
}
label 
{
    font-weight: bold;
    font-family: calibri;
}
button
{
    font-weight: bold;
    font-size: x-large;
}
.fa-right-to-bracket
{
    position: relative;
    right: 10px;
}
.fa-clipboard
{
    font-size: 20px;
    position: relative;
    right: 10px;
}
</style>

</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr>

<div class="adminlogin">
<center><i class="fa fa-user-shield"></i></center>
<h2>Admin Login</h2>

<form method="POST">

<label><i class="fa fa-user"></i>USERNAME</label>
<input type="text" name="username" placeholder="Enter Username" required>
<br>
<label><i class="fa fa-lock"></i>PASSWORD</label>
<input type="password" name="password" placeholder="Enter Password" required>

<button type="submit" name="login"><i class="fa-solid fa-right-to-bracket"></i>LOGIN</button>

</form>
<div class="back-link">
    <a href="index.php"><i class="fa-solid fa-clipboard"></i>BACK TO MAIN PAGE</a>
</div>
<?php if($message!=""){ ?>

<div id="msgBox" class="msg <?php echo $type; ?>">

<?php
if($type=="success"){
    echo '<i class="fa-solid fa-circle-check"></i> ';
}
else if($type=="error"){
    echo '<i class="fa-solid fa-circle-xmark"></i> ';
}
?>

<?php echo $message; ?>

</div>

<?php } ?>

</div>

<script>
setTimeout(function(){
var msg=document.getElementById("msgBox");
if(msg){msg.style.display="none";}
},3000);
</script>

</body>
</html>