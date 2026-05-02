<?php
$conn = mysqli_connect("localhost","root","","engg-college_exam_result");

if($conn){
    echo "Connected Successfully";
}else{
    echo "Connection Failed";
}
?>