<?php
include("db.php");

require('fpdf/fpdf.php');

$sem = $_GET['sem'];

$query = mysqli_query($conn, "
SELECT students.name, students.rollno,
       marks.Total, marks.Percentage, marks.Result
FROM students
LEFT JOIN marks 
ON students.rollno = marks.rollno AND students.sem = marks.sem
WHERE students.sem='$sem'
ORDER BY 
    CASE WHEN marks.Percentage IS NULL THEN 1 ELSE 0 END,
    marks.Percentage DESC
");

$pdf = new FPDF();
$pdf->AddPage();


// ================== HEADER ==================

// 🏫 Logo (make sure logo.png exists)
$pdf->Image('images/Desh Bhagat College emblem design.png', 10, 0, 25);

// Move down to avoid overlap
$pdf->Ln(10);

// 🏫 College Name
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,15,"DESH BHAGAT COLLEGE OF ENGINEERING AND TECHNOLOGY",0,1,'C');

// Subtitle
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Rank List - Semester $sem",0,1,'C');

// Line separator
$pdf->SetDrawColor(0,0,0);
$pdf->Line(10, 44, 200, 44);
$pdf->Ln(10);


// ================== TABLE HEADER ==================

$pdf->SetFont('Arial','B',10);

$pdf->Cell(20,10,"Rank",1);
$pdf->Cell(40,10,"Name",1);
$pdf->Cell(25,10,"Roll",1);
$pdf->Cell(25,10,"Total",1);
$pdf->Cell(30,10,"Percentage",1);
$pdf->Cell(25,10,"Result",1);
$pdf->Ln();


// ================== DATA ==================

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

    $pdf->Cell(20,10, $hasMarks ? $rank : "-",1);
    $pdf->Cell(40,10, $row['name'],1);
    $pdf->Cell(25,10, $row['rollno'],1);
    $pdf->Cell(25,10, $hasMarks ? $row['Total'] : "N/A",1);
    $pdf->Cell(30,10, $hasMarks ? $row['Percentage']."%" : "N/A",1);
    $pdf->Cell(25,10, $hasMarks ? $row['Result'] : "Pending",1);
    $pdf->Ln();
}


// ================== OUTPUT ==================

$pdf->Output();
?>