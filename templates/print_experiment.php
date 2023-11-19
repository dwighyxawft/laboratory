<?php
require('../fpdf186/fpdf.php');
include("../middleware/objects.php");
$objects = new Objects;

$date = date("Y-m-d");
$time = date("H:i:s");
$exp_id = $_GET["experiment_id"];
$objects->query = "SELECT * FROM experiments WHERE id = '$exp_id'";
$experiment = $objects->query_result();
$objects->query = "SELECT * FROM reports WHERE exp_id = '$exp_id'";
$report = $objects->query_result();

// Create a PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(false); // Disable automatic page breaks


// Define the maximum Y position before starting a new page
$maxY = 280; // Adjust this value based on your layout

// Function to check if a new page is needed
function checkNewPage($pdf, $maxY) {
    if ($pdf->GetY() > $maxY) {
        $pdf->AddPage(); // Add a new page
        $pdf->SetY(10); // Reset Y position to the top of the new page
    }
}



// Add the experiment title below the image
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, $experiment["name"], 0, 1, 'C');
checkNewPage($pdf, $maxY);

// Add some spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Now, add the "Apparatus Used" div
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, $apparatus["apparatus_name"], 0, 1);
checkNewPage($pdf, $maxY);


$objects->query = "SELECT * FROM apparatus_used WHERE exp_id = '$exp_id'";
$apps = $objects->query_all();
foreach($apps as $app){
    $app_id = $app["app_id"];
    $objects->query = "SELECT * FROM apparatus WHERE id = '$app_id'";
    $apparatus = $objects->query_result();
    // Apparatus Cards
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 5, $apparatus["apparatus_name"], 0, 1);
    checkNewPage($pdf, $maxY);
    $pdf->MultiCell(0, 5, 'Usage: '. $apparatus["apparatus_usage"], 0, 1);
    checkNewPage($pdf, $maxY);
}





// Add some more spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Add the "Experiment Report" section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Experiment Report', 0, 1);
checkNewPage($pdf, $maxY);
// Add some more spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Procedure
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, 'Procedure', 0, 1);
$pdf->MultiCell(0, 5, $report["procedures"], 0, 1);
checkNewPage($pdf, $maxY);
// Add some more spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Result
$pdf->Cell(0, 5, 'Result', 0, 1);
$pdf->MultiCell(0, 5, $report["results"], 0, 1);
checkNewPage($pdf, $maxY);
// Add some more spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Conclusion
$pdf->Cell(0, 5, 'Conclusion', 0, 1);
$pdf->MultiCell(0, 5, $report["conclusion"], 0, 1);
checkNewPage($pdf, $maxY);

// Add some more spacing
$pdf->Ln(20);
checkNewPage($pdf, $maxY);

// Finally, add the "Students Attendance" div
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Students Attendance', 0, 1);
checkNewPage($pdf, $maxY);
// Add some more spacing
$pdf->Ln(10);
checkNewPage($pdf, $maxY);

// Student cards (similar to the apparatus cards) can be added here
$objects->query = "SELECT * FROM attendance WHERE exp_id = '$exp_id'";
$students = $objects->query_all();
foreach($students as $student){
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 5, $student["student_name"], 0, 1);
    checkNewPage($pdf, $maxY);
    $pdf->MultiCell(0, 5, 'Matric: '. $student["student_matric"], 0, 1);
    checkNewPage($pdf, $maxY);
    // Add some more spacing
    $pdf->Ln(5);
    checkNewPage($pdf, $maxY);  
}

// Output the PDF
$pdf_filePath = $pdf->Output('../pdfs/'.$date.$exp_id.'experiment.pdf', 'F'); // 'F' saves the PDF to a file
echo $objects->redirect("pdfs/".$pdf_filePath."");




?>