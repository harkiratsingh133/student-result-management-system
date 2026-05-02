<?php
session_start();
include("db.php");

if(!isset($_SESSION['faculty'])){
    header("Location: faculty_login.php");
    exit();
}

$email = $_SESSION['faculty'];

// Get faculty semester
$f = mysqli_query($conn, "SELECT sem FROM faculty WHERE email='$email'");
$fdata = mysqli_fetch_assoc($f);
$sem = $fdata['sem'];

// Get students
$students = mysqli_query($conn, "SELECT * FROM students WHERE sem='$sem' ORDER BY rollno ASC");

// Get subjects
$sub = mysqli_query($conn, "SELECT * FROM subjects WHERE sem='$sem'");
$subjects = mysqli_fetch_assoc($sub);

// MESSAGE VARIABLES
$msg = "";
$type = "";

// ✅ ADD THIS (for dropdown memory)
$selectedRoll = "";

// SUBMIT MARKS
if(isset($_POST['submit'])){

    $rollno = $_POST['rollno'];

    // ✅ STORE SELECTED VALUE
    $selectedRoll = $rollno;

    // CHECK STUDENT EXISTS
    $getname = mysqli_query($conn, "SELECT name FROM students WHERE rollno='$rollno' AND sem='$sem'");

    if(mysqli_num_rows($getname) == 0){
        $msg = "Invalid Roll Number or Student Not Found";
        $type = "error";
    } 
    else {

        // ✅ STRICT DUPLICATE CHECK (FIXED BUG)
        $check = mysqli_query($conn, "SELECT 1 FROM marks WHERE rollno='$rollno' AND sem='$sem' LIMIT 1");

        if(mysqli_num_rows($check) > 0){

            $msg = "Marks already submitted for this Roll No";
            $type = "error";

        } else {

            $n = mysqli_fetch_assoc($getname);
            $name = $n['name'];

            $Subject1 = $_POST['s1'];
            $Subject2 = $_POST['s2'];
            $Subject3 = $_POST['s3'];
            $Subject4 = $_POST['s4'];
            $Subject5 = $_POST['s5'];

            $Total = $Subject1 + $Subject2 + $Subject3 + $Subject4 + $Subject5;
            $Percentage = $Total / 5;

            if($Percentage < 40){
                $Result = "Fair";
            } elseif($Percentage < 60){
                $Result = "Good";
            } elseif($Percentage < 80){
                $Result = "Better";
            } else {
                $Result = "Best";
            }

            $certificate_id = "CERT-" . date("Ymd") . "-" . rand(1000,9999);

            $insert = mysqli_query($conn, "INSERT INTO marks 
            (name, rollno, sem, Subject1, Subject2, Subject3, Subject4, Subject5, Total, Percentage, Result, certificate_id)
            VALUES 
            ('$name','$rollno','$sem','$Subject1','$Subject2','$Subject3','$Subject4','$Subject5','$Total','$Percentage','$Result','$certificate_id')");

            if($insert){
                $msg = "Successfully added marks (Roll No: $rollno - $name)";
                $type = "success";
            } else {
                $msg = "Database Error: " . mysqli_error($conn);
                $type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Marks</title>

<link rel="stylesheet" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
h2 { text-align:center; }

label { font-weight:bold; font-size: x-large;}

select, input {
    width:100%;
    padding:8px;
    margin:8px 0;
    border-radius:6px;
    border:1px solid #ccc;
}
button 
{
    font-weight: bold;
    font-size: x-large;
}
/* ✅ MESSAGE CSS */
.msg {
    width:320px;
    margin:15px auto;
    padding:12px;
    text-align:center;
    border-radius:8px;
    color:white;
    font-weight:bold;
    animation: fadeIn 0.3s ease;
}
.msg i{
margin-right:6px;
}
.success {
    background:#28a745;
}

.error {
    background:#dc3545;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(-10px); }
    to { opacity:1; transform:translateY(0); }
}

/* HOME BUTTON */
.home-btn {
    display:block;
    width:150px;
    margin:20px auto;
    text-align:center;
    padding:10px;
    background:#001f3f;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight: bold;
    font-size: x-large;
    position: relative;
    bottom: 50px;
}
h2 
{
    color: white;
}
.fa-house 
{
    position: relative;
    right: 5px;
}
.fa-floppy-disk 
{
    position: relative;
    right: 5px;
}
.submarks 
{
    width: 600px;
    margin: 50px auto;
    padding: 30px;
    background: rgba(255, 255, 255, 1);
    border-radius: 15px;
    text-align: center;
    position: relative;
    bottom: 30px;
}
.submarks:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
    transition: 0.3s ease;
}
.fa-id-badge 
{
    font-size: 35px;
    color: navy;
    position: relative;
    right: 10px;
}
</style>
</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<h2>SUBMIT MARKS (SEM <?php echo $sem; ?>)</h2>

<!-- ✅ MESSAGE DISPLAY -->
<!-- ✅ MESSAGE DISPLAY -->
<?php if($msg != ""){ ?>
<div class="msg <?php echo $type; ?>" id="msgBox">

<?php
if($type == "success"){
    echo '<i class="fa-solid fa-circle-check"></i> ';
}
else if($type == "error"){
    echo '<i class="fa-solid fa-circle-xmark"></i> ';
}
?>

<?php echo $msg; ?>

</div>

<script>
setTimeout(() => {
    document.getElementById("msgBox").style.display = "none";
}, 2000);
</script>
<?php } ?>

<div class="submarks">
<form method="POST" action="">
<i class="fa-solid fa-id-badge"></i>
<label>SELECT ROLL NO</label>
<select name="rollno">
<?php while($row = mysqli_fetch_assoc($students)){ ?>
    <option value="<?php echo $row['rollno']; ?>" 
    <?php if($selectedRoll == $row['rollno']) echo "selected"; ?>>
        <?php echo $row['rollno'] . " - " . $row['name']; ?>
    </option>
<?php } ?>
</select>

<label><?php echo $subjects['Subject1']; ?></label>
<input type="number" name="s1" max="100" required>

<label><?php echo $subjects['Subject2']; ?></label>
<input type="number" name="s2" max="100" required>

<label><?php echo $subjects['Subject3']; ?></label>
<input type="number" name="s3" max="100" required>

<label><?php echo $subjects['Subject4']; ?></label>
<input type="number" name="s4" max="100" required>

<label><?php echo $subjects['Subject5']; ?></label>
<input type="number" name="s5" max="100" required>

<button name="submit"><i class="fa-solid fa-floppy-disk"></i>SUBMIT</button>

</form>
</div>

<a href="faculty_homepage.php" class="home-btn"><i class="fa-solid fa-house"></i>HOME</a>

</body>
</html>