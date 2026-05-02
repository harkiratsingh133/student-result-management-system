<?php
include("db.php");

$id = $_POST['id'];
$name = $_POST['name'];
$rollno = $_POST['rollno'];
$email = $_POST['email'];

$update = mysqli_query($conn,
"UPDATE students SET 
name='$name',
rollno='$rollno',
email='$email'
WHERE id='$id'");

if($update){
    header("Location: viewusers.php?msg=updated");
} else {
    echo "Update failed";
}
?>