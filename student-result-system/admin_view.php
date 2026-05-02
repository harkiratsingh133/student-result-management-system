<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$table = $_GET['table'];
$username = $_SESSION['admin'];

// QUERY
if($table == "students"){
    $sql = "SELECT * FROM students ORDER BY sem ASC, rollno ASC";
}
else if($table == "marks"){
    $sql = "SELECT students.name, students.rollno, students.sem, marks.*
            FROM marks
            JOIN students 
            ON marks.rollno = students.rollno 
            AND marks.sem = students.sem
            ORDER BY students.sem ASC, marks.Percentage DESC";
}
else if($table == "faculty"){
    $sql = "SELECT * FROM faculty ORDER BY sem ASC";
}
else if($table == "subjects"){
    $sql = "SELECT * FROM subjects ORDER BY sem ASC";
}
else{
    $sql = "SELECT * FROM $table";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin View</title>

<link rel="stylesheet" href="/student-result-system/admin_style.css">

<style>
/* ===== TABLE CONTAINER ===== */
table{
    width:95%;
    margin:20px auto;
    border-collapse:collapse;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    border-radius:12px;
    overflow:hidden;
}

/* ===== HEADER ===== */
th{
    background: linear-gradient(135deg, #007bff, #0056b3);
    color:white;
    padding:12px;
    font-size:16px;
    text-transform:uppercase;
    border:1px solid rgba(255,255,255,0.2);
}

/* ===== CELLS ===== */
td{
    padding:10px;
    text-align:center;
    color:#ffffff;
    font-size:15px;
    border:1px solid rgba(255,255,255,0.2);
    background: rgba(0,0,0,0.25);
    transition:0.3s ease;
}

/* ===== ROW HOVER EFFECT ===== */
tr:hover td{
    background: rgba(0,123,255,0.25);
    transform: scale(1.01);
    color:#fff;
}

/* ===== TABLE TITLE ROW ===== */
.table-title td{
    background:#28a745 !important;
    color:white;
    font-size:20px;
    font-weight:bold;
    padding:12px;
    text-align:center;
}

/* ===== SEMESTER MERGED CELL ===== */
.sem-cell{
    background: rgba(255,255,255,0.15);
    font-weight:bold;
    color:#fff;
}

/* ===== SEARCH BOX ===== */
.search-box{
    width:320px;
    padding:10px;
    margin:20px auto;
    display:block;
    border-radius:8px;
    border:none;
    outline:none;
    font-size:15px;
    background: rgba(255,255,255,0.9);
}

/* ===== HIGHLIGHT (SEARCH) ===== */
.highlight{
    background:yellow;
    color:black;
    font-weight:bold;
}

/* ===== NO RECORD ===== */
.no-record{
    text-align:center;
    color:#ff4d4d;
    font-weight:bold;
    margin-top:10px;
    display:none;      /* ✅ hidden by default */
    opacity:0;
    transition:0.3s;
}

.no-record.show{
    display:block;
    opacity:1;
}

/* ===== DASHBOARD BUTTON ===== */
.dashboard-link{
    display:block;
    width:220px;
    margin:30px auto;
    padding:12px;
    text-align:center;
    background:#001f3f;
    color:white;
    text-decoration:none;
    font-weight:bold;
    font-size:18px;
    border-radius:8px;
    transition:0.3s;
}

.dashboard-link:hover{
    background:#007bff;
    transform:scale(1.05);
}

/* ===== TOPPER COLORS ===== */
.gold{
    color:gold;
    font-weight:bold;
}

.silver{
    color:silver;
    font-weight:bold;
}

.bronze{
    color:#cd7f32;
    font-weight:bold;
}
</style>
</head>

<body>

<h2 style="text-align:center;color:white;">
<?php echo strtoupper($username); ?> Viewing <?php echo ucfirst($table); ?>
</h2>

<input type="text" id="searchInput" onkeyup="filterTable()" 
placeholder="Search here..." class="search-box">

<p id="noRecord" class="no-record">No Records Found</p>

<center>
<table id="marksTable">

<thead>

<tr class="table-title">
<td colspan="
<?php
if($table=="marks") echo 9;
else if($table=="students") echo 5;
else if($table=="faculty") echo 5;
else if($table=="subjects") echo 7;
else echo 5;
?>">
<?php echo strtoupper($table)." TABLE"; ?>
</td>
</tr>

<tr>

<?php if($table=="marks"){ ?>
<th>Rank</th>
<th>Name</th>
<th>Roll</th>
<th>Semester</th>
<th>Total</th>
<th>%</th>
<th>Grade</th>
<th>Result</th>
<th>Certificate</th>

<?php } else if($table=="students"){ ?>
<th>ID</th>
<th>Name</th>
<th>Roll</th>
<th>Semester</th>
<th>Email</th>

<?php } else if($table=="faculty"){ ?>
<th>ID</th>
<th>Name</th>
<th>Semester</th>
<th>Email</th>
<th>Password</th>

<?php } else if($table=="subjects"){ ?>
<th>ID</th>
<th>Subject1</th>
<th>Subject2</th>
<th>Subject3</th>
<th>Subject4</th>
<th>Subject5</th>
<th>Semester</th>

<?php } ?>

</tr>

</thead>

<tbody>

<?php

// ================= MARKS =================
if($table=="marks"){

$data=[];
while($row=mysqli_fetch_assoc($result)){
    $data[]=$row;
}

// GROUP BY SEM FOR RANK
$ranked = [];

foreach($data as $row){
    $ranked[$row['sem']][] = $row;
}

// APPLY RANK PER SEM
foreach($ranked as $sem => $students){

    usort($students, function($a,$b){
        return $b['Percentage'] <=> $a['Percentage'];
    });

    $rank=1;
    foreach($students as $row){

        echo "<tr>";

        echo "<td>".$rank."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['rollno']."</td>";
        echo "<td>Sem ".$row['sem']."</td>";
        echo "<td>".$row['Total']."</td>";
        echo "<td>".$row['Percentage']."%</td>";

        $grade = ($row['Percentage']>=80)?"A":(($row['Percentage']>=60)?"B":(($row['Percentage']>=40)?"C":"F"));
        echo "<td>$grade</td>";

        echo "<td>".$row['Result']."</td>";
        echo "<td>".$row['certificate_id']."</td>";

        echo "</tr>";

        $rank++;
    }
}

// 🔥 TOP 3
echo "</tbody></table>

<h3 style='text-align:center; color:white'>🏆 TOP 3 STUDENTS</h3>
<table style='width:60%;margin:auto;'>
<tr><th>Rank</th><th>Name</th><th>Roll</th><th>%</th></tr>";

$top = $data;
usort($top,function($a,$b){
    return $b['Percentage'] <=> $a['Percentage'];
});

$top = array_slice($top,0,3);

$r=1;
foreach($top as $t){
echo "<tr>";
echo "<td>".($r==1?"🥇":($r==2?"🥈":"🥉"))."</td>";
echo "<td>".$t['name']."</td>";
echo "<td>".$t['rollno']."</td>";
echo "<td>".$t['Percentage']."</td>";
echo "</tr>";
$r++;
}

}

// ================= STUDENTS =================
else if($table=="students"){

while($row=mysqli_fetch_assoc($result)){
echo "<tr>";
echo "<td>".$row['id']."</td>";
echo "<td>".$row['name']."</td>";
echo "<td>".$row['rollno']."</td>";
echo "<td>".$row['sem']."</td>";
echo "<td>".$row['email']."</td>";
echo "</tr>";
}

}

// ================= FACULTY =================
else if($table=="faculty"){

while($row=mysqli_fetch_assoc($result)){
echo "<tr>";
echo "<td>".$row['id']."</td>";
echo "<td>".$row['name']."</td>";
echo "<td>".$row['sem']."</td>";
echo "<td>".$row['email']."</td>";
echo "<td>".$row['password']."</td>";
echo "</tr>";
}

}

// ================= SUBJECTS =================
else if($table=="subjects"){

while($row=mysqli_fetch_assoc($result)){
echo "<tr>";
echo "<td>".$row['id']."</td>";
echo "<td>".$row['Subject1']."</td>";
echo "<td>".$row['Subject2']."</td>";
echo "<td>".$row['Subject3']."</td>";
echo "<td>".$row['Subject4']."</td>";
echo "<td>".$row['Subject5']."</td>";
echo "<td>".$row['sem']."</td>";
echo "</tr>";
}

}

?>

</tbody>
</table>
</center>

<a href="admin_dashboard.php" class="dashboard-link">
DASHBOARD
</a>

<script>
function filterTable(){

let input = document.getElementById("searchInput");
let value = input.value.toLowerCase();
let rows = document.querySelectorAll("#marksTable tbody tr");
let count = 0;

rows.forEach(row => {

let cells = row.querySelectorAll("td");
let found = false;

cells.forEach(cell => {

let text = cell.innerText;

if(text.toLowerCase().includes(value)){
found = true;
}

});

if(found || value === ""){
row.style.display = "";
count++;
}else{
row.style.display = "none";
}

});

let noRecord = document.getElementById("noRecord");

if(noRecord){
    if(count === 0){
        noRecord.classList.add("show");
    } else {
        noRecord.classList.remove("show");
    }
}
}
</script>

</body>
</html>