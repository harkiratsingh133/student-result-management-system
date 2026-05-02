<?php
session_start();
include("db.php");

// Show errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";
$msg_type = "";

if (isset($_POST['login'])) {

    // ✅ TRIM INPUT (fix space issue)
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ PREVENT SQL INJECTION (basic safety)
    $email = mysqli_real_escape_string($conn, $email);

    // ✅ FETCH USER
    $query = "SELECT * FROM faculty WHERE LOWER(email)=LOWER('$email')";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {

        // 🔥 IMPORTANT CHECK
        if (password_verify($password, $row['password'])) {

            $_SESSION['faculty'] = $email;
            $_SESSION['email']   = $row['email'];
            $_SESSION['name']    = $row['name'];
            $_SESSION['sem']     = $row['sem'];

            $message = "LOGIN SUCCESSFUL";
            $msg_type = "success";

            // ✅ FIXED REDIRECT (ABSOLUTE PATH)
            echo "<script>
                setTimeout(function(){
                    window.location.href='/student-result-system/faculty_homepage.php';
                }, 2000);
            </script>";

        } else {
            $message = "Email or Password Incorrect";
            $msg_type = "error";
        }

    } else {
        $message = "Email or Password Incorrect";
        $msg_type = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Login</title>
    <link rel="stylesheet" href="/student-result-system/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .msg {
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            text-align: center;
            animation: fadeOut 2s forwards;
        }

        .success {
            background-color: #28a745;
        }

        .error {
            background-color: #dc3545;
        }

        @keyframes fadeOut {
            0% {opacity: 1;}
            80% {opacity: 1;}
            100% {opacity: 0; display: none;}
        }

        .main-page-link
        {
            
        }

        i
        {
            font-size: 35px;
            color: navy;
            position: relative;
            right: 7px;
        }

        .fa-clipboard
        {
            color: white;
            position: relative;
            right: 5px;
            font-size: 25px;
        }

        button
        {
            font-weight: bold;
            font-size: x-large;
        }

        .fa-right-to-bracket
        {
            color: white;
            position: relative;
            top: 4px;
        }

        .msg i{
            margin-right:6px;
            font-size:18px;
            color:white;
            font-weight: bolder;
        }
    </style>
</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<div class="container">
    
    <h2><i class="fa fa-chalkboard-teacher"></i> Faculty Login</h2>
    <form method="POST">
        <label><i class="fa fa-envelope"></i>EMAIL</label>
        <input type="email" name="email" placeholder="Enter Email ID" required>
        <br><br>
        <label><i class="fa fa-lock"></i>PASSWORD</label>
        <input type="password" name="password" placeholder="Enter Password" required>

        <button type="submit" name="login"><i class="fa-solid fa-right-to-bracket"></i>LOGIN</button>

    </form>

    <!-- ✅ MESSAGE DISPLAY -->
    <?php if (!empty($message)) { ?>
        <div class="msg <?php echo $msg_type; ?>">
            <?php 
            if($msg_type == "success"){
                echo '<i class="fa-solid fa-circle-check"></i> ';
            } else {
                echo '<i class="fa-solid fa-circle-xmark"></i> ';
            }
            echo $message; 
            ?>
        </div>
    <?php } ?>

    <div class="links">
        <a href="/student-result-system/index.php"><i class="fa-solid fa-clipboard"></i>MAIN PAGE</a>
    </div>

</div>

</body>
</html>