<?php 
session_start(); 
include("db.php"); 

// 🔒 Check login 
if(!isset($_SESSION['faculty'])){ 
    header("Location: faculty_login.php"); 
    exit(); 
} 

$email = $_SESSION['faculty']; 

// 👉 Get faculty semester 
$f = mysqli_query($conn, "SELECT sem FROM faculty WHERE email='$email'"); 
$fdata = mysqli_fetch_assoc($f); 
$sem = $fdata['sem']; 

$message = "";
$type = "";

// 👉 SAVE SUBJECTS 
if(isset($_POST['submit'])){ 
    $sub1 = $_POST['sub1']; 
    $sub2 = $_POST['sub2']; 
    $sub3 = $_POST['sub3']; 
    $sub4 = $_POST['sub4']; 
    $sub5 = $_POST['sub5']; 

    // ✅ CHECK IF SUBJECTS ALREADY EXIST FOR THIS SEM
    $check = mysqli_query($conn, "SELECT * FROM subjects WHERE sem='$sem'");

    if(mysqli_num_rows($check) > 0){
        $message = "Subjects already added for sem $sem";
        $type = "error";
    } else {

        // ✅ INSERT SUBJECTS
        $insert = mysqli_query($conn, "INSERT INTO subjects 
        (sem, subject1, subject2, subject3, subject4, subject5) 
        VALUES ('$sem','$sub1','$sub2','$sub3','$sub4','$sub5')"); 

        if($insert){ 
            $message = "Successfully Saved Subjects";
            $type = "success";
        } else { 
            $message = "Error saving subjects";
            $type = "error";
        } 
    }
} 
?> 

<!DOCTYPE html> 
<html> 
<head> 
<title>Submit Subjects</title> 

<!-- External CSS --> 
<link rel="stylesheet" href="/student-result-system/style.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style> 
.container { 
    width: 400px; 
    margin: 40px auto; 
    background: rgba(255,255,255,1); 
    padding: 25px; 
    border-radius: 12px; 
    text-align: center; 
    box-shadow: 0 0 15px rgba(0,0,0,0.2); 
} 

input { 
    width: 90%; 
    padding: 10px; 
    margin: 8px 0; 
    border-radius: 6px; 
    border: 1px solid #ccc; 
} 

button { 
    padding: 10px 20px; 
    background: green; 
    color: white; 
    border: none; 
    border-radius: 6px; 
    font-weight: bold; 
    cursor: pointer; 
} 

/* ✅ MESSAGE STYLING */
.msg {
    width: 320px;
    margin: 10px auto;
    padding: 12px;
    text-align: center;
    border-radius: 6px;
    font-weight: bold;
}
.msg i{
    margin-right:6px;
}
/* SUCCESS */
.success {
    background: #28a745;
    color: white;
}

/* ERROR */
.error {
    background: #dc3545;
    color: white;
}
/* HOME LINK BUTTON */
.home-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: #001f3f; /* Navy Blue */
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
    font-size: x-large;
}

.home-link:hover {
    background: #003366;
    transform: scale(1.05);
}
.fa-house
{
    position: relative;
    right: 5px;
}
.fa-book 
{
    color: navy;
    position: relative;
    right: 10px;
}
.fa-floppy-disk 
{
    position: relative;
    right: 10px;
}
.fa-book-open 
{
    font-size: 35px;
    color: navy;
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

<!-- ✅ MESSAGE DISPLAY -->
<!-- ✅ MESSAGE DISPLAY -->
<?php if($message != "") { ?>
    <div class="msg <?php echo $type; ?>" id="msgBox">

        <?php
        if($type == "success"){
            echo '<i class="fa-solid fa-circle-check"></i> ';
        }
        else if($type == "error"){
            echo '<i class="fa-solid fa-circle-xmark"></i> ';
        }
        ?>

        <?php echo $message; ?>

    </div>

    <script>
        setTimeout(() => {
            document.getElementById("msgBox").style.display = "none";
        }, 2000);
    </script>
<?php } ?>
<div class="container"> 
<i class="fa-solid fa-book-open"></i><br>
<h2>SUBMIT SUBJECTS <br>(SEMESTER <?php echo $sem; ?>)</h2> 

<form method="POST">

<label><i class="fa fa-book"></i>SUBJECT 1</label>
<input type="text" name="sub1" placeholder="SUBJECT 1" required>

<label><i class="fa fa-book"></i>SUBJECT 2</label>
<input type="text" name="sub2" placeholder="SUBJECT 2" required>

<label><i class="fa fa-book"></i>SUBJECT 3</label>
<input type="text" name="sub3" placeholder="SUBJECT 3" required>

<label><i class="fa fa-book"></i>SUBJECT 4</label>
<input type="text" name="sub4" placeholder="SUBJECT 4" required>

<label><i class="fa fa-book"></i>SUBJECT 5</label>
<input type="text" name="sub5" placeholder="SUBJECT 5" required> 

<button type="submit" name="submit"><i class="fa-solid fa-floppy-disk"></i>SAVE SUBJECTS</button> 
</form> 
<a href="faculty_homepage.php" class="home-link"><i class="fa-solid fa-house"></i>HOME</a>
</div> 

</body> 
</html>