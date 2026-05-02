<?php
session_start();
include("db.php");

// 🔒 Check login
if(!isset($_SESSION['faculty'])){
    header("Location: faculty_login.php");
    exit();
}

$email = $_SESSION['faculty'];
$msg = "";

// 👉 CHANGE PASSWORD LOGIC
if(isset($_POST['change'])){

    $old = $_POST['oldpass'];
    $new = $_POST['newpass'];
    $re  = $_POST['repass'];

    // Get current password from DB
    $res = mysqli_query($conn, "SELECT password FROM faculty WHERE email='$email'");
    $data = mysqli_fetch_assoc($res);

    // If you used password_hash()
    if(!password_verify($old, $data['password'])){
        header("Location: changepassword.php?msg=wrong");
        exit();
    }

    // Check new password match
    if($new != $re){
        header("Location: changepassword.php?msg=notmatch");
        exit();
    }

    // Hash new password
    $newpass = password_hash($new, PASSWORD_DEFAULT);

    // Update password
    $update = mysqli_query($conn, "UPDATE faculty SET password='$newpass' WHERE email='$email'");

    if($update){
        header("Location: changepassword.php?msg=success");
        exit();
    } else {
        echo "Error updating password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>

<!-- External CSS -->
<link rel="stylesheet" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>

input {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button 
{
    font-weight: bold;
    font-size: x-large;
    
}
.success i, .error i{
    margin-right:6px;
}
/* Messages */
.success {
    width: 300px;
    margin: 10px auto;
    padding: 10px;
    background: #28a745;
    color: white;
    text-align: center;
    border-radius: 6px;
}

.error {
    width: 300px;
    margin: 10px auto;
    padding: 10px;
    background: #dc3545;
    color: white;
    text-align: center;
    border-radius: 6px;
}

/* HOME LINK */
.home-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #001f3f;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    font-size: x-large;
}
.fa-house 
{
    position: relative;
    right: 7px;
}
.change-pwd 
{
    width: 500px;
    margin: 50px auto;
    padding: 30px;
    background: rgba(255, 255, 255, 1);
    border-radius: 15px;
    text-align: center;
}
.change-pwd:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}
.fa-key 
{
    font-size: 35px;
    color: navy;
}
.fa-lock 
{
    font-size: 35px;
    color: navy;
}
.fa-check-double 
{
    font-size: 35px;
    color: navy;
}
#change-pwd 
{
    color: white;
}
.fa-check 
{
    position: relative;
    right: 10px;
}
</style>

</head>
<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<!-- ✅ MESSAGE DISPLAY -->
<!-- ✅ MESSAGE DISPLAY -->
<?php if(isset($_GET['msg'])){ ?>

    <?php if($_GET['msg'] == 'success'){ ?>
        <div class="success" id="msg">
            <i class="fa-solid fa-circle-check"></i> Successfully Changed Password
        </div>
    <?php } ?>

    <?php if($_GET['msg'] == 'wrong'){ ?>
        <div class="error" id="msg">
            <i class="fa-solid fa-circle-xmark"></i> Incorrect Old Password
        </div>
    <?php } ?>

    <?php if($_GET['msg'] == 'notmatch'){ ?>
        <div class="error" id="msg">
            <i class="fa-solid fa-circle-xmark"></i> New Password Didn't Match
        </div>
    <?php } ?>

<script>
setTimeout(()=>{
    document.getElementById("msg").style.display = "none";
},3000);
</script>

<?php } ?>

<div class="change-pwd">
<i class="fa-solid fa-key"></i><br>
<h2>UPDATE PASSWORD FOR FACULTY</h2>

<form method="POST">
<i class="fa-solid fa-lock"></i>
<label>OLD PASSWORD</label>
<input type="password" name="oldpass" placeholder="ENTER OLD PASSWORD" required>

<i class="fa-solid fa-key"></i>
<label>NEW PASSWORD</label>
<input type="password" name="newpass" placeholder="NEW PASSWORD" required><br>

<i class="fa-solid fa-check-double"></i>
<label>RE-TYPE NEW PASSWORD</label>
<input type="password" name="repass" placeholder="RE-TYPE NEW PASSWORD" required>

<button type="submit" name="change"><i class="fa-solid fa-check"></i>CHANGE PASSWORD</button>

</form>

<!-- HOME LINK -->
<a href="faculty_homepage.php" class="home-link"><i class="fa-solid fa-house" id="change-pwd"></i>HOME</a>

</div>

</body>
</html>