<?php
session_start();
include("db.php"); // you can change this
if(isset($_GET['rollno']) && isset($_GET['sem'])){

    $rollno = $_GET['rollno'];
    $sem = $_GET['sem'];

    $query = mysqli_query($conn, "SELECT * FROM marks WHERE rollno='$rollno' AND sem='$sem'");
    $data = mysqli_fetch_assoc($query);

    if(mysqli_num_rows($query) == 0){
        header("Location: index.php?msg=norecord");
        exit();
    }

    $sub = mysqli_query($conn, "SELECT * FROM subjects WHERE sem='$sem'");
    $subjects = mysqli_fetch_assoc($sub);

    $stu = mysqli_query($conn, "SELECT * FROM students WHERE rollno='$rollno'");
    $student = mysqli_fetch_assoc($stu);

    $year = date("Y");
    $certificate_id = "DBCET-" . $rollno . "-" . $sem . "-" . rand(1000,9999);
} else {
    header("Location: index.php");
    exit();
}
$percentage = $data['Percentage'];

if($percentage >= 95){
    $remark = "DISTINCTION";
}
elseif($percentage >= 90){
    $remark = "FIRST CLASS WITH HONOURS";
}
elseif($percentage >= 85){
    $remark = "FIRST CLASS";
}
elseif($percentage >= 80){
    $remark = "SECOND CLASS";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Result</title>

<link rel="stylesheet" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.result-body 
{
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-image: url('images/final background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 200vh;
}

/* MARKSHEET DESIGN */
.marksheet{
    width: 210mm;
    max-height: 297mm;
    margin: 0px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-sizing: border-box;
    font-size: 17px;

    position: relative; /* ✅ IMPORTANT for stamp positioning */
    z-index: 1;
    transform: scale(0.98);
    transform-origin: top center;
}
.marksheet h2{
    margin:5px;
}

.sub-heading{
    font-size:21px;
    margin-bottom:10px;
    font-weight: bold;
    text-transform: uppercase;
}

.student-info{
    text-align:left;
    margin:15px 0;
}

.student-info p{
    margin:5px 0;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    border:1px solid black; /* ✅ FIX outer border */
}

th, td{
    border:1px solid black;
    padding:8px;
    text-align:center;
}

th{
    background:#007bff;
    color:white;
}

/* FORCE RIGHT BORDER FIX */
tr td:last-child,
tr th:last-child{
    border-right:1px solid black;
}

/* SUMMARY */
.summary{
    margin-top:15px;
    text-align:left;
    font-weight:bold;
}

/* SIGNATURE */
.signatures{
    display:flex;
    justify-content:space-between;
    margin-top:40px;
}

.sign-box{
    text-align:center;
    position: relative;
    top: 20px;
    
}

.sign-img{
    width:120px;
    height:auto;
    margin-bottom:5px;
    opacity:0.9;
    position: relative;
    top: 40px;
}

.sign-name{
    font-weight:bold;
    margin:2px 0;
}

.sign-role{
    font-size:13px;
    color:#333;
}

/* HEADER */
.header{
    text-align:center;
}

.logo{
    width:150px;
    margin-bottom:5px;
}

/* ✅ FIXED STAMP POSITION (NO LAYOUT BREAK) */
.stamp-box{
    position: absolute;
    bottom: 120px;   /* adjust if needed */
    right: 60px;
}

.stamp{
    width:160px;        /* ✅ reduced size */
    opacity:0.7;
    transform:rotate(-15deg);
}

/* BUTTON */
.pdf-btn{
    display:block;
    margin:20px auto;
    padding:10px 20px;
    background:#FF2C2C;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    width:200px;
}
.pdf-btn:hover
{
    background-color: #000000
}
/* BACK BUTTON */
.home-btn{
    display:block;
    width:150px;
    margin:20px auto;
    text-align:center;
    padding:10px;
    background:#000000;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
.home-btn:hover
{
    background-color: #000080;
}
.remark{
    color:#2B0071;
    font-weight:bold;
    font-size:18px;
}
.watermark{
    position:absolute;
    top:45%;
    left:50%;
    transform:translate(-50%, -50%) rotate(-30deg);
    font-size:80px;
    color:rgba(0,0,0,0.08);
    font-weight:bold;
    z-index:0;
    pointer-events:none;
    font-size:70px;
    color:rgba(0,0,0,0.05);
    letter-spacing:5px;
}
.certificate-id{
    text-align:right;
    font-weight:bold;
    color:#2B0071;
    font-size:14px;
}
</style>
</head>

<body class="result-body">

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<!-- ✅ MARKSHEET -->
<div class="marksheet" id="marksheet">
<div class="watermark">OFFICIAL DOCUMENT</div>
<div class="header">
    <img src="images/Desh Bhagat College emblem design.png" class="logo">
    <h2>DESH BHAGAT COLLEGE OF ENGINEERING & TECHNOLOGY</h2>
    <p class="sub-heading">Official Marksheet</p>
</div>
<hr>
<h3>SEMESTER <?php echo $sem; ?> RESULT</h3>

<div class="student-info">
<p><strong>Name:</strong> <?php echo $student['name']; ?></p>
<p><strong>Roll No:</strong> <?php echo $rollno; ?></p>
<p><strong>Semester:</strong> <?php echo $sem; ?></p>
</div>
<p class="certificate-id">
Certificate ID: <?php echo $certificate_id; ?>
</p>
<table>
<tr>
<th>Subject Code</th>
<th>Subject Name</th>
<th>Marks</th>
<th>Max Marks</th>
</tr>

<tr>
<td>SUB1</td>
<td><?php echo $subjects['Subject1']; ?></td>
<td><?php echo $data['Subject1']; ?></td>
<td>100</td>
</tr>

<tr>
<td>SUB2</td>
<td><?php echo $subjects['Subject2']; ?></td>
<td><?php echo $data['Subject2']; ?></td>
<td>100</td>
</tr>

<tr>
<td>SUB3</td>
<td><?php echo $subjects['Subject3']; ?></td>
<td><?php echo $data['Subject3']; ?></td>
<td>100</td>
</tr>

<tr>
<td>SUB4</td>
<td><?php echo $subjects['Subject4']; ?></td>
<td><?php echo $data['Subject4']; ?></td>
<td>100</td>
</tr>

<tr>
<td>SUB5</td>
<td><?php echo $subjects['Subject5']; ?></td>
<td><?php echo $data['Subject5']; ?></td>
<td>100</td>
</tr>

</table>

<div class="summary">
<p>Total Marks: <?php echo $data['Total']; ?> / 500</p>
<p>Percentage: <?php echo $data['Percentage']; ?>%</p>
<p>Result: <?php echo $data['Result']; ?></p>
<p>REMARK: <span class="remark"><?php echo $remark; ?></span></p>
</div>

<div class="signatures">

<div class="sign-box">
    <img src="images/rajesh kumar sign.png" class="sign-img">
    <p class="sign-name">Mr. Rajesh Kumar</p>
    <p class="sign-role">Exam Controller</p>
</div>

<div class="sign-box">
    <img src="images/Handwritten signature of Taranjeet Singh.png" class="sign-img">
    <p class="sign-name">Dr. Taranjeet Singh</p>
    <p class="sign-role">Principal</p>
</div>
</div>
<div class="stamp-box">
    <img src="images/desh bhagat college stamp.png" class="stamp">
</div>
</div>

<!-- ✅ DOWNLOAD BUTTON -->
<button id="downloadBtn" class="pdf-btn">
<i class="fa fa-file-pdf"></i> Download Marksheet
</button>

<a href="index.php" class="home-btn">
<i class="fa-solid fa-arrow-left"></i> BACK
</a>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="/student-result-system/js/html2pdf.bundle.min.js"></script>

<script>
const studentName = "<?php echo $student['name']; ?>";
const semester = "<?php echo $sem; ?>";

document.addEventListener("DOMContentLoaded", function(){

    const btn = document.getElementById("downloadBtn");

    btn.addEventListener("click", function(){

        const element = document.getElementById("marksheet");

        html2pdf()
            .set({
                margin: 0,
                filename: `Marksheet_Sem${semester}_${studentName}.pdf`,
                html2canvas: { scale: 2, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['avoid-all'] }
            })
            .from(element)
            .save();

    });

});
</script>

</body>
</html>