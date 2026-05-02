<?php
session_start();
include("db.php");
if(!isset($_SESSION['email']))
{
    header("Location: index.php");
    exit();
}
if(isset($_GET['id'])){
    $id = $_GET['id'];

    $delete = mysqli_query($conn, "DELETE FROM students WHERE id='$id'");

    if($delete){
        header("Location: viewusers.php?msg=deleted");
    } else {
        echo "Error deleting";
    }
}
?>