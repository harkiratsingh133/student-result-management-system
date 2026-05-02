<?php
session_start();
include("db.php");

// 🔒 Check login
if(!isset($_SESSION['faculty'])){
    echo "<tr><td colspan='5'>Session expired. Please login again.</td></tr>";
    exit();
}

$email = $_SESSION['faculty'];

// 🎯 Get faculty semester
$faculty_query = mysqli_query($conn, "SELECT sem FROM faculty WHERE email='$email'");
$faculty_data = mysqli_fetch_assoc($faculty_query);

if(!$faculty_data){
    echo "<tr><td colspan='5'>Faculty not found</td></tr>";
    exit();
}

$sem = $faculty_data['sem'];

// 🔍 Get search value (IMPORTANT: use GET)
$search = "";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// 🎯 Query (ALWAYS filter by semester)
if($search != ""){
    $query = "SELECT * FROM students 
              WHERE sem='$sem' AND (
                name LIKE '%$search%' OR 
                rollno LIKE '%$search%' OR 
                email LIKE '%$search%'
              )";
} else {
    $query = "SELECT * FROM students WHERE sem='$sem'";
}

$result = mysqli_query($conn, $query);

// 🎨 Highlight function
function highlight($text, $search){
    if(empty($search)) return $text;
    return preg_replace("/(" . preg_quote($search, '/') . ")/i", "<span style='background:yellow;'>$1</span>", $text);
}

// 📋 Output
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
            <td>".highlight($row['name'], $search)."</td>
            <td>".highlight($row['rollno'], $search)."</td>
            <td>".highlight($row['email'], $search)."</td>
            <td>".$row['sem']."</td>
            <td>
                <a href='edit_student.php?id=".$row['id']."' 
                   onclick=\"return confirm('Are you sure you want to Update?')\" 
                   style='padding:6px 10px;background:green;color:white;border-radius:5px;text-decoration:none;'>
                   Update
                </a>

                <a href='delete.php?id=".$row['id']."' 
                   onclick=\"return confirm('Are you sure you want to delete?')\" 
                   style='padding:6px 10px;background:red;color:white;border-radius:5px;text-decoration:none;'>
                   Delete
                </a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5' style='color:red;'>No records found</td></tr>";
}

?>