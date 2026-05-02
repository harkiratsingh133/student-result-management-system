<?php
include("db.php");

// ✅ GET SELECTED SEM (DEFAULT = 1)
$sem = $_GET['sem'] ?? 1;

// ✅ FETCH SEMESTERS FOR DROPDOWN (DYNAMIC)
$semQuery = mysqli_query($conn, "SELECT DISTINCT sem FROM students ORDER BY sem ASC");

if(!$semQuery){
    die("Error fetching semesters: " . mysqli_error($conn));
}

// ✅ MAIN QUERY
$query = mysqli_query($conn, "
SELECT students.name, students.rollno, students.sem,
       marks.Total, marks.Percentage, marks.Result
FROM students
LEFT JOIN marks 
ON students.rollno = marks.rollno AND students.sem = marks.sem
WHERE students.sem='$sem'
ORDER BY 
    CASE WHEN marks.Percentage IS NULL THEN 1 ELSE 0 END,
    marks.Percentage DESC
");

if(!$query){
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Rank-wise Results</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: Arial, sans-serif;
    background-image: url('images/final background.jpg');
}

.container{
    width: 90%;
    margin: auto;
    margin-top: 20px;
}

h2{
    text-align: center;
    color: #FFFFFF;
}

form{
    text-align: center;
    margin-bottom: 15px;
}

select, button{
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-weight: bold;
}

button{
    background: #007bff;
    color: white;
    cursor: pointer;
}

button:hover{
    background: #0056b3;
}

table{
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 20px;
}

th, td{
    padding: 10px;
    text-align: center;
}

th{
    background: #343a40;
    color: white;
}

tr:nth-child(even){
    background: #f2f2f2;
}

tr:hover{
    background: #e9f5ff;
}

.pending{
    color: red;
    font-weight: bold;
}

.gold{ color: gold; }
.silver{ color: silver; }
.bronze{ color: #cd7f32; }

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
</style>
</head>

<body>

<h2>🏆 Rank-wise Result - Semester <?php echo htmlspecialchars($sem); ?></h2>

<!-- ✅ DROPDOWN FIXED -->
<form method="GET">
<select name="sem" required>

<option value="">-- Select Semester --</option>

<?php while($row = mysqli_fetch_assoc($semQuery)){ 
    $s = $row['sem'];
?>
<option value="<?php echo $s; ?>" 
<?php if($sem == $s) echo "selected"; ?>>
Semester <?php echo $s; ?>
</option>
<?php } ?>

</select>

<button type="submit">View</button>
</form>

<!-- PDF -->
<div style="text-align:center;">
<a href="rank_pdf.php?sem=<?php echo urlencode($sem); ?>" target="_blank">
<button>Download PDF</button>
</a>
</div>

<div class="container">

<div style="text-align:center; margin: 15px 0;">
<input type="text" id="searchInput" placeholder="🔍 Search..."
style="width: 50%; padding:10px; border-radius:8px; border:1px solid #ccc;">
</div>

<table id="rankTable">
<thead>
<tr>
<th>Rank</th>
<th>Name</th>
<th>Roll No</th>
<th>Total</th>
<th>Percentage</th>
<th>Result</th>
</tr>
</thead>

<tbody>

<?php
$rank = 0;
$prevPercentage = null;
$actualRank = 0;

while($row = mysqli_fetch_assoc($query)){

$hasMarks = ($row['Percentage'] !== null);

if($hasMarks){
    $actualRank++;

    if($row['Percentage'] != $prevPercentage){
        $rank = $actualRank;
    }

    $prevPercentage = $row['Percentage'];
}
?>

<tr>

<td>
<?php 
if($hasMarks){

if($rank == 1){
echo "<i class='fa-solid fa-trophy gold'></i> ".$rank;
}
elseif($rank == 2){
echo "<i class='fa-solid fa-medal silver'></i> ".$rank;
}
elseif($rank == 3){
echo "<i class='fa-solid fa-award bronze'></i> ".$rank;
}
else{
echo $rank;
}

}else{
echo "-";
}
?>
</td>

<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['rollno']); ?></td>

<td>
<?php echo $hasMarks ? htmlspecialchars($row['Total']) : "<span class='pending'>TO BE EVALUATED</span>"; ?>
</td>

<td>
<?php echo $hasMarks ? htmlspecialchars($row['Percentage'])."%" : "<span class='pending'>TO BE EVALUATED</span>"; ?>
</td>

<td>
<?php echo $hasMarks ? htmlspecialchars($row['Result']) : "<span class='pending'>TO BE EVALUATED</span>"; ?>
</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>

<a href="admin_dashboard.php" class="dashboard-link">
<i class="fa-solid fa-gauge"></i> DASHBOARD
</a>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {

let filter = this.value.toLowerCase();
let rows = document.querySelectorAll("#rankTable tbody tr");

rows.forEach(function(row) {
let text = row.innerText.toLowerCase();
row.style.display = text.includes(filter) ? "" : "none";
});

});
</script>

</body>
</html>