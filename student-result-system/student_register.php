<?php
session_start();
include("db.php");

$message = "";
$type = "";

if(isset($_POST['register'])){

    $name  = trim($_POST['name']);
    $sem   = $_POST['sem'];
    $email = trim($_POST['email']);

    // 🔒 BACKEND SAFETY (Marks lock)
    $checkMarks = mysqli_query($conn, "SELECT * FROM marks WHERE sem='$sem'");
    if(mysqli_num_rows($checkMarks) > 0){
        $message = "❌ Registration Closed (Marks Entry Started for $sem)";
        $type = "error";
    }

    else{

        // ❌ EMAIL DUPLICATE CHECK
        $checkEmail = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
        if(mysqli_num_rows($checkEmail) > 0){
            $message = "❌ Email Already Exists";
            $type = "error";
        }

        else{

            // ✅ SIMPLE INCREMENT SYSTEM + RANGE CONTROL

            // Get last roll number of that semester
            $getLast = mysqli_query($conn, "
                SELECT MAX(rollno) as lastRoll 
                FROM students 
                WHERE sem='$sem'
            ");

            $lastData = mysqli_fetch_assoc($getLast);

            // 🎯 DEFINE RANGE
            $start = ($sem * 100) + 1;   // 101, 201, 301...
            $end   = ($sem * 100) + 40;  // 140, 240, 340...

            // 🎯 GENERATE ROLL
            if($lastData['lastRoll'] == NULL){
                $newRoll = $start;
            } else {
                $newRoll = $lastData['lastRoll'] + 1;
            }

            // ❌ RANGE LIMIT CHECK (FINAL FIX)
            if($newRoll > $end){
                $message = "Maximum 40 students allowed in Semester $sem (Range: $start - $end)";
                $type = "error";
            }

            else{

                // ✅ INSERT
                $insert = mysqli_query($conn, "
                    INSERT INTO students (name, rollno, sem, email)
                    VALUES ('$name', '$newRoll', '$sem', '$email')
                ");

                if($insert){
                    $message = "✅ Student Registered Successfully (Roll No: $newRoll)";
                    $type = "success";
                } else {
                    $message = "❌ Error: " . mysqli_error($conn);
                    $type = "error";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>

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

.msg i{
margin-right:6px;
color:white;
font-size:18px;
}

.success{
    background-color:#28a745;
}

.error{
    background-color:#dc3545;
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

i{
    font-size:35px;
    color:navy;
}

button{
    font-size:x-large;
    font-weight:bold;
}

.fa-clipboard{
    color:white;
    position:relative;
    right:7px;
}

.fa-house{
    color:white;
    position:relative;
    right:7px;
}
.warning-box {
    width: 90%;
    max-width: 400px;
    margin: 15px auto;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    background-color: #FFE135;
    color: #28231D;
    border: 1px solid #ffeeba;
    display: flex;
    align-items: center;
    justify-content: center;

}
.warning-box i {
    margin-right: 8px;
    font-size: 16px;
}

</style>
</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<div class="container">

<i class="fa fa-user-plus"></i>
<h2>Student Registration</h2>
<div id="warningBox" class="warning-box" style="display:none;">
    <i class="fa-solid fa-triangle-exclamation"></i>
    Registration Closed (Marks Already Entered)
</div>
<form method="POST">

<label><i class="fa fa-user"></i> Student Name</label>
<input type="text" name="name" id="nameField" placeholder="Enter Name" required>

<label><i class="fa fa-layer-group"></i> Semester</label>

<select name="sem" id="semSelect" required>
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

<label><i class="fa fa-envelope"></i> Email</label>
<input type="email" name="email" id="emailField" placeholder="Enter Email ID" required>

<button type="submit" id="registerBtn" name="register">
    REGISTER
</button>
<div class="links">
<a href="index.php">
<i class="fa-solid fa-house"></i>MAIN PAGE
</a>
</div>
</form>

<!-- MESSAGE -->

<?php if($message!=""){ ?>
<div class="msg <?php echo $type; ?>">
    <i class="fa-solid 
    <?php 
    if($type=="success") echo "fa-circle-check";
    elseif($type=="error") echo "fa-circle-xmark";
    else echo "fa-triangle-exclamation";
    ?>"></i>
    <?php echo $message; ?>
</div>
<?php } ?>

</div>

</div>

<script>
setTimeout(function(){
var msg=document.getElementById("msgBox");
if(msg){
msg.style.display="none";
}
},3000);
</script>

<script>
document.getElementById("semSelect").addEventListener("change", function(){

    let sem = this.value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "sem_lock_status.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function(){
        if(this.responseText.trim() === "locked"){

            document.getElementById("nameField").disabled = true;
            document.getElementById("emailField").disabled = true;
            document.getElementById("registerBtn").disabled = true;

            document.getElementById("warningBox").style.display = "block";

        } else {

            document.getElementById("nameField").disabled = false;
            document.getElementById("emailField").disabled = false;
            document.getElementById("registerBtn").disabled = false;

            document.getElementById("warningBox").style.display = "none";
        }
    };

    xhr.send("sem=" + sem);
});
</script>
</body>
</html>