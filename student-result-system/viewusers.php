<?php
session_start();
include("db.php");

if(!isset($_SESSION['email']))
{
    header("Location: index.php");
    exit();
}

$email = $_SESSION['faculty'];

// Get faculty semester
$f = mysqli_query($conn, "SELECT sem FROM faculty WHERE email='$email'");
$fdata = mysqli_fetch_assoc($f);
$sem = $fdata['sem'];

// Get students
$result = mysqli_query($conn, "SELECT * FROM students WHERE sem='$sem' ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Students</title>

<style>
.viewusersbody 
{
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-image: url('images/final background.jpg'); /* put your image in same folder */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 130vh;
}
h2 { text-align:center; }

/* Search */
.search-box {
    width:300px;
    padding:10px;
    margin:20px auto;
    display:block;
    border-radius:8px;
}

/* Table */
table {
    width:90%;
    margin:auto;
    border-collapse:collapse;
    background:white;
}

th, td {
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}
th { background:#007bff; color:white; }

/* Buttons */
.btn {
    padding:6px 12px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}

.delete-btn { background:#dc3545; }
.update-btn { background:#28a745; }

/* Highlight */
.highlight { background:yellow; font-weight:bold; }

/* Hidden rows */
.hidden { display:none; }

/* No record */
.no-record {
    text-align:center;
    color:red;
    font-weight:bold;
    display:none;
}

/* Success message */
.success {
    width:300px;
    margin:10px auto;
    padding:10px;
    background:#28a745;
    color:white;
    text-align:center;
    border-radius:6px;
}

/* MODAL */
.modal {
    display:none;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    position: fixed;
    bottom: 0px;
}

.modal-content {
    background:white;
    padding:20px;
    margin:15% auto;
    width:300px;
    text-align:center;
    border-radius:10px;
}

.confirm { background:red; color:white; padding:8px; }
.cancel { background:gray; color:white; padding:8px; }
.home-link {
    display: block;
    width: 150px;
    margin: 30px auto;
    text-align: center;
    padding: 10px;
    background-color: #001f3f; /* Navy Blue */
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
    font-size: x-large;
}

.home-link:hover {
    background-color: #007bff;
    transform: scale(1.05);
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
.icon-success{
margin-right:6px;
font-size:18px;
}

.icon-delete{
color:red;
margin-right:6px;
font-size:18px;
}

.icon-update{
color: navy;
margin-right:6px;
font-size:18px;
}
.fa-magnifying-glass 
{
    color: white;
    font-size: 35px;
    position: absolute;
    left: 730px;
}
</style>
<link rel="stylesheet" type="text/css" href="/student-result-system/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="viewusersbody">
<h1 class="main-heading">STUDENT RESULT MANAGEMENT SYSTEM</h1>
<hr class="main-line">
<h2>REGISTERED STUDENTS - SEMESTER <?php echo $sem; ?></h2>

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'){ ?>
<div class="success" id="msg">
<i class="fa-solid fa-circle-check icon-success"></i> Student Updated Successfully
</div>

<script>
setTimeout(function(){
    document.getElementById("msg").style.display="none";
},3000);
</script>

<?php } ?>


<?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'){ ?>
<div class="success" id="msg">
<i class="fa-solid fa-trash icon-success"></i> Student Deleted Successfully
</div>

<script>
setTimeout(function(){
    document.getElementById("msg").style.display="none";
},3000);
</script>

<?php } ?>
<i class="fa-solid fa-magnifying-glass"></i>
<input type="text" id="searchInput" onkeyup="filterTable()" class="search-box" placeholder="Search...">

<p id="noRecord" class="no-record">No Records Found</p>

<table id="studentTable">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Roll No</th>
    <th>Email</th>
    <th>Sem</th>
    <th>Action</th>
</tr>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['rollno']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['sem']; ?></td>

<td>
<a href="updatestudent.php?id=<?php echo $row['id']; ?>" 
   class="btn update-btn"
   onclick="return confirmAction(event,this.href,'update')">Update</a>

<a href="deletestudent.php?id=<?php echo $row['id']; ?>" 
   class="btn delete-btn"
   onclick="return confirmAction(event,this.href,'delete')">Delete</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<a href="faculty_homepage.php" class="home-link"><i class="fa-solid fa-house"></i>HOME</a>

<!-- MODAL -->
<div id="modal" class="modal">
<div class="modal-content">
<p id="modalText"></p>
<button class="confirm" onclick="goAction()">Yes</button>
<button class="cancel" onclick="closeModal()">Cancel</button>
</div>
</div>

<script>
let actionLink="";

// Live search + highlight
function filterTable(){
    let input=document.getElementById("searchInput").value.toLowerCase();
    let rows=document.querySelectorAll("#studentTable tbody tr");
    let count=0;

    rows.forEach(row=>{
        let cells=row.querySelectorAll("td");
        let found=false;

        cells.forEach((cell, index) => {

    // 🚫 SKIP ACTION COLUMN (last column)
    if(index === cells.length - 1) return;

    let text = cell.innerText;
    cell.innerHTML = text;

    if(input !== "" && text.toLowerCase().includes(input)){
        found = true;
        let regex = new RegExp(`(${input})`, "gi");
        cell.innerHTML = text.replace(regex, "<span class='highlight'>$1</span>");
    }
});

        if(found || input===""){
            row.classList.remove("hidden");
            count++;
        } else {
            row.classList.add("hidden");
        }
    });

    document.getElementById("noRecord").style.display = count===0 ? "block":"none";
}

// Modal
function confirmAction(e,link,type){
    e.preventDefault();
    actionLink=link;

   if(type==="delete"){
    document.getElementById("modalText").innerHTML =
    "<i class='fa-solid fa-triangle-exclamation icon-delete'></i> Are you sure to delete?";
}
else{
    document.getElementById("modalText").innerHTML =
    "<i class='fa-solid fa-pen icon-update'></i> Are you sure to update?";
}

    document.getElementById("modal").style.display="block";
}

function closeModal(){
    document.getElementById("modal").style.display="none";
}

function goAction(){
    window.location.href=actionLink;
}
</script>

</body>
</html>