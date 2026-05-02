<?php
include("db.php");

$message = "";
$message_type = "";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $sem = $_POST['sem'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $checkEmail = "SELECT * FROM faculty WHERE email='$email'";
    $emailResult = mysqli_query($conn, $checkEmail);

    // Check if semester already has faculty
    $checkSem = "SELECT * FROM faculty WHERE sem='$sem'";
    $semResult = mysqli_query($conn, $checkSem);

    if(mysqli_num_rows($emailResult) > 0){
        $message = "Email Already Exists";
        $message_type = "error";
    }

    else if(mysqli_num_rows($semResult) > 0){
        $message = "Not more than one Faculty allowed for Any Semester (Semester 1 to 8)";
        $message_type = "error";
    }

    else{

        $sql = "INSERT INTO faculty (name, sem, email, password)
                VALUES ('$name', '$sem', '$email', '$password')";

        if(mysqli_query($conn, $sql)){
            $message = "Successfully Registered";
            $message_type = "success";
        }
        else{
            $message = "Error Saving Data";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Faculty Registration</title>
<link rel="stylesheet" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>

.msg{
    padding:12px;
    margin-top:15px;
    border-radius:8px;
    color:white;
    font-weight:bold;
    text-align:center;
    animation:fadeOut 3s forwards;
}
.msg i
{
margin-right:6px;
color: white;
}
.success{
    background:#28a745;
}

.error{
    background:#dc3545;
}

@keyframes fadeOut{
    0%{opacity:1;}
    80%{opacity:1;}
    100%{opacity:0;display:none;}
}

select{
    width:95%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}
.fa-user-tie
{
    font-size: 35px;
    color: navy;
}
i 
{
    color: navy;
}
.fa-clipboard 
{
    color: white;
    position: relative;
    right: 5px;
}
button
{
    font-weight: bold;
    font-size: x-large;
}

</style>

</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<div class="container">
<label><i class="fa fa-user-tie"></i>
<h2>Faculty Registration</h2>

<form method="POST">
<label><i class="fa fa-chalkboard-teacher"></i>FACULTY NAME</label>
<input type="text" name="name" placeholder="Enter Name" required>

<label><i class="fa fa-layer-group"></i>SEMESTER</label>
<select name="sem" required>
<option value="">Select Semester</option>
<option value="1">Semester 1</option>
<option value="2">Semester 2</option>
<option value="3">Semester 3</option>
<option value="4">Semester 4</option>
<option value="5">Semester 5</option>
<option value="6">Semester 6</option>
<option value="7">Semester 7</option>
<option value="8">Semester 8</option>
</select>
<br>
<label><i class="fa fa-envelope"></i>EMAIL</label>
<input type="email" name="email" placeholder="Enter Email ID" required>
<br>
<label><i class="fa fa-lock"></i>PASSWORD</label>
<input type="password" name="password" placeholder="Enter Password" required>

<button type="submit" name="register"><i class="fa-solid fa-clipboard"></i>REGISTER</button>

</form>

<!-- Message -->
<!-- Message -->
<?php if($message != ""): ?>
<div id="msgBox" class="msg <?php echo $message_type; ?>">

<?php
if($message_type == "success"){
    echo '<i class="fa-solid fa-circle-check"></i> ';
}
else if($message_type == "error"){
    echo '<i class="fa-solid fa-circle-xmark"></i> ';
}
?>

<?php echo $message; ?>

</div>
<?php endif; ?>

<div class="links">
<a href="/student-result-system/index.php"><i class="fa-solid fa-clipboard"></i>MAIN PAGE</a>
</div>

</div>

<script>
setTimeout(function(){
var msg = document.getElementById("msgBox");
if(msg){
msg.style.display="none";
}
},3000);
</script>

</body>
</html>