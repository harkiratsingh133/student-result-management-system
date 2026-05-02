<?php
session_start();
include("db.php");

if(!isset($_SESSION['faculty'])){
    header("Location: faculty_login.php");
    exit();
}

$email = $_SESSION['faculty'];

// Get faculty semester
$f = mysqli_query($conn,"SELECT sem FROM faculty WHERE email='$email'");
$fdata = mysqli_fetch_assoc($f);
$sem = $fdata['sem'];

// KEEP ROLL ORDER
$query = mysqli_query($conn,"
SELECT students.name, students.rollno, students.sem, marks.*
FROM students
JOIN marks 
ON students.rollno = marks.rollno 
AND students.sem = marks.sem
WHERE students.sem='$sem'
ORDER BY students.rollno ASC
");

// STORE DATA
$studentsData = [];
while($row = mysqli_fetch_assoc($query)){
    $studentsData[] = $row;
}

// RANK LOGIC (for icons only)
$rankData = $studentsData;

usort($rankData, function($a, $b){
    return $b['Percentage'] <=> $a['Percentage'];
});

// TOP 5
$top5 = array_slice($rankData, 0, 5);
$topRanks = [];
$pos = 1;

foreach($top5 as $t){
    $topRanks[$t['rollno']] = $pos;
    $pos++;
}

// TOP 3
$topStudents = array_slice($rankData, 0, 3);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Students Marks</title>

<link rel="stylesheet" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
table{width:95%;margin:auto;border-collapse:collapse;background:white;}
th{background:#007bff;color:white;padding:10px;}
td{padding:8px;text-align:center;border-bottom:1px solid #ddd;}

h2{color:white;}
h3{color:#FFFFFF;}

/* SEARCH BOX */
.search-box{
width:300px;
padding:10px;
margin:20px auto;
display:block;
border-radius:8px;
border:1px solid #ccc;
}

/* HIGHLIGHT */
.highlight{
background:yellow;
font-weight:bold;
}

/* ICON CSS */
.name-cell{transition:0.3s;}

.rank-icon{
margin-right:8px;
font-size:18px;
transition:0.3s;
cursor:pointer;
}

.gold{color:gold;text-shadow:0 0 8px gold;}
.silver{color:silver;text-shadow:0 0 6px silver;}
.bronze{color:#cd7f32;text-shadow:0 0 6px #cd7f32;}
.top5{color:#007bff;}

.rank-icon:hover{
transform:scale(1.4) rotate(10deg);
text-shadow:0 0 15px white;
}

tr:hover .name-cell{
font-weight:bold;
letter-spacing:0.5px;
}

/* Grade */
.grade-A{color:green;font-weight:bold;}
.grade-B{color:blue;font-weight:bold;}
.grade-C{color:orange;font-weight:bold;}
.grade-F{color:red;font-weight:bold;}

/* No record */
.no-record{
text-align:center;
color:red;
font-weight:bold;
display:none;
margin-top:10px;
}

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
.fa-magnifying-glass 
{
    color: white;
    font-size: 35px;
    position: absolute;
    left: 730px;
}
</style>
</head>

<body>

<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">

<h2 style="text-align:center;">SEMESTER <?php echo $sem; ?> MARKS</h2>

<!-- 🔍 SEARCH BOX -->
<i class="fa-solid fa-magnifying-glass"></i>
<input type="text" id="searchInput" onkeyup="filterTable()" 
placeholder="Search student..." class="search-box">

<p id="noRecord" class="no-record">No Records Found</p>

<table id="marksTable">

<thead>
<tr>
<th>Name</th>
<th>Roll No</th>
<th>Total</th>
<th>Percentage</th>
<th>Grade</th>
<th>Result</th>
</tr>
</thead>

<tbody>

<?php foreach($studentsData as $row){ ?>

<tr>

<td class="name-cell">
<?php
$roll = $row['rollno'];

if(isset($topRanks[$roll])){
    $p = $topRanks[$roll];

    if($p==1) echo "<span class='rank-icon gold'>🥇</span>";
    elseif($p==2) echo "<span class='rank-icon silver'>🥈</span>";
    elseif($p==3) echo "<span class='rank-icon bronze'>🥉</span>";
    elseif($p==4 || $p==5) echo "<span class='rank-icon top5'>🏅</span>";
}

echo $row['name'];
?>
</td>

<td><?php echo $row['rollno']; ?></td>
<td><?php echo $row['Total']; ?></td>
<td><?php echo $row['Percentage']; ?>%</td>

<td class="<?php
if($row['Percentage']>=80) echo 'grade-A';
elseif($row['Percentage']>=60) echo 'grade-B';
elseif($row['Percentage']>=40) echo 'grade-C';
else echo 'grade-F';
?>">
<?php
if($row['Percentage']>=80) echo "A";
elseif($row['Percentage']>=60) echo "B";
elseif($row['Percentage']>=40) echo "C";
else echo "F";
?>
</td>

<td><?php echo $row['Result']; ?></td>

</tr>

<?php } ?>

</tbody>
</table>

<h3 style="text-align:center;">🏆 TOP 3 STUDENTS</h3>

<table style="width:60%;margin:auto;">
<tr><th>Rank</th><th>Name</th><th>Roll</th><th>%</th></tr>

<?php $r=1; foreach($topStudents as $t){ ?>
<tr>
<td><?php echo ($r==1?"🥇":($r==2?"🥈":"🥉")); ?></td>
<td><?php echo $t['name']; ?></td>
<td><?php echo $t['rollno']; ?></td>
<td><?php echo $t['Percentage']; ?>%</td>
</tr>
<?php $r++; } ?>

</table>

<a href="faculty_homepage.php" class="dashboard-link">
<i class="fa-solid fa-house"></i> HOME
</a>

<!-- 🔥 SEARCH SCRIPT -->
<script>
function filterTable(){

let input = document.getElementById("searchInput").value.toLowerCase();
let rows = document.querySelectorAll("#marksTable tbody tr");
let count = 0;

rows.forEach(row => {

let cells = row.querySelectorAll("td");
let found = false;

cells.forEach(cell => {

let text = cell.innerText;
cell.innerHTML = text;

if(input !== "" && text.toLowerCase().includes(input)){
found = true;

// highlight
let regex = new RegExp(`(${input})`, "gi");
cell.innerHTML = text.replace(regex, "<span class='highlight'>$1</span>");
}

});

if(found || input === ""){
row.style.display = "";
count++;
}else{
row.style.display = "none";
}

});

document.getElementById("noRecord").style.display = count===0 ? "block":"none";
}
</script>

</body>
</html>