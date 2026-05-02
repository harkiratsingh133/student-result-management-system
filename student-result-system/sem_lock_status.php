<?php
include("db.php");

if(isset($_POST['sem'])){
    $sem = $_POST['sem'];

    $check = mysqli_query($conn, "SELECT * FROM marks WHERE sem='$sem'");

    if(mysqli_num_rows($check) > 0){
        echo "locked";
    } else {
        echo "open";
    }
}
?>