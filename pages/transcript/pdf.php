<?php

session_start();

include("../../config/dbc.php");
include("../../library/transcript_functions.php");
include("../../library/fpdf.php");

$idNumber = $_GET['idNumber'];

$stInfo = $db->query("SELECT * FROM stud_info WHERE idnum=$idNumber")->fetch_object();
$details = $db->query("SELECT * FROM transcript_details WHERE idnum=$idNumber")->fetch_object();

class PDF extends FPDF
{
    function Footer()
    {
        global $details;

        $this->SetY(-60);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(105, 10, "OR. No.: $details->orno", 0, 0, 'L');
        $this->Cell(0, 10, "Date Issued: " . date('F d, Y', strtotime($details->or_date)), 0, 0, 'R');
        $this->Ln(8);
        $this->Cell(40, 10, "REMARKS:", 0, 0, 'L');
        $this->Cell(0, 10, $details->remarks, 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(40, 10, "Date Issued:", 0, 0, 'L');
        $this->Cell(0, 10, date('F d, Y', strtotime($details->date_issued)), 0, 0, 'L');
        $this->Ln(8);
        $this->Cell(0, 10, "Not Valid Without", 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(0, 10, "MDC Seal", 0, 0, 'L');
        $this->Ln(8);
        $this->Cell(135, 10, 'Page ' . $this->PageNo() . " of {nb} pages", 0, 0, 'L');
        $this->Cell(0, 10, "JOSE RUEL B. ALAMPAYAN", 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(135, 10, "");
        $this->Cell(0, 10, "Registrar", 0, 0, 'C');
    }

    function firstPage()
    {
        global $stInfo;
        global $details;

        $this->AddPage();
        $this->SetFont('Arial', '', 11);
        $this->SetY(45);
        $this->Cell(0,6,"Revised $details->revised",0,0,'L');
        $this->SetY(65);
        $this->SetFont('Arial', 'B', 12);

        $this->Cell(15, 6, "Name: ", 0, 0, 'L');
        $this->Cell(115, 6, "$stInfo->lname, $stInfo->fname $stInfo->mi", 'B', 1, 'L');

        $this->Cell(45, 6, 'Student Number:', 0, 0, 'L');
        $this->Cell(30, 6, "$stInfo->idnum-$stInfo->idext", 'B', 0, 'L');
        $this->Cell(18, 6, "    Sex:", 0, 0, 'L');
        $this->Cell(37, 6, "$stInfo->gender", 'B', 1, 'L');

        $this->Cell(45, 6, "Date of Birth:", 0, 0, 'L');
        $this->Cell(85, 6, date('F d, Y', strtotime($stInfo->bdate)), 'B', 1, 'L');

        $this->Cell(45, 6, "Place of Birth:", 0, 0, 'L');
        $this->Cell(85, 6, $details->place_of_birth, 'B', 1, 'L');

        $this->Cell(45, 6, "Nationality:", 0, 0, 'L');
        $this->Cell(85, 6, $details->nationality, 'B', 1, 'L');

        $this->Cell(45, 6, "Religion:", 0, 0, 'L');
        $this->Cell(85, 6, $details->religion, 'B', 1, 'L');

        $this->Cell(45, 6, "Parent/Guardians:", 0, 0, 'L');
        $this->Cell(85, 6, $details->guardians, 'B', 1, 'L');

        $this->Cell(45, 6, "          Address:", 0, 0, 'L');
        $this->Cell(85, 6, $details->gd_address, 'B', 1, 'L');

        $this->Cell(45, 6, "Home Address:", 0, 0, 'L');
        $this->Cell(85, 6, strtoupper($stInfo->addb) . ', ' . ucfirst(strtolower($stInfo->addt)) . ', ' . ucfirst(strtolower($stInfo->addp)), 'B', 1, 'L');

        $this->Cell(45, 6, "Date Admitted:", 0, 0, 'L');
        $this->Cell(85, 6, date('F d, Y', strtotime($details->dt_admitted)), 'B', 1, 'L');

        $this->Cell(45, 6, "Entrance Data:", 0, 0, 'L');
        $this->Cell(85, 6, $details->entrance_data, 'B', 1, 'L');

        $this->Cell(45, 6, "College of:", 0, 0, 'L');
        $this->Cell(85, 6, $details->college, 'B', 1, 'L');

        $this->Cell(45,6,"Preliminary Education:",0,1,'L');

        $this->Cell(45,6,"",0,0,'L');
        $this->Cell(90,6, "Name of School",1,0,'C');
        $this->Cell(0,6,'School Year',1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Elementary: ",0,0,'L');
        $this->Cell(90,6, $details->elem,1,0,'L');
        $this->Cell(0,6,$details->elem_sy, 1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Secondary: ",0,0,'L');
        $this->Cell(90,6, $details->secn,1,0,'L');
        $this->Cell(0,6,$details->secn_sy, 1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Tertiary: ",0,0,'L');
        $this->Cell(90,6, $details->tcry,1,0,'L');
        $this->Cell(0,6,$details->tcry_sy, 1,1,'C');

        //check availability of picture
        $file = "../../images/portraits/$stInfo->idnum.jpg";
        if(file_exists($file)) {
            $this->Image($file, 145, 62, 61, 0);
        }

        //grading system image..
        $this->Image('../../images/grade_table.png', 11, 170, 195);
    }

    function renderSem($sem, $rows) {
        $lh = 5;
        $this->SetFont('Arial','B',12);
        $this->Cell(0,5.5,"{$sem['sy']} - {$sem['school']}",'B',1,'L');
        $this->Ln(2);
        $this->SetFont('Arial','',11);
        foreach($rows as $row) {
            $this->Cell(45,$lh,$row['course'],0,0,'L');
            $this->Cell(120,$lh,strtoupper($row['description']),0,0,'L');
            $this->Cell(15,$lh,$row['rating'], 0,0,'C');
            $this->Cell(15,$lh,$row['units'], 0,1,'C');
        }
        $this->Ln(2);
    }


}

if (!isset($_SESSION['user'])) header("location:index.php");

if (isset($_GET['idNumber'])) {

    $pdf = new PDF('P', 'mm', [215.9, 330.2]);
    $pdf->AliasNbPages();
    $pdf->firstPage();

    //distribute semesters to pages...
    $limit = 33;
    $rowCount = $limit;
    $sems = getSems($idNumber);
    foreach($sems as $sem) {
        $rows = getSemRows($sem['id']);
        $incoming_rows = count($rows) + 1;

        if( ($rowCount + $incoming_rows) > $limit) {
            $pdf->AddPage();
            $pdf->SetY(65);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(140,5.5, "Name: $stInfo->lname, $stInfo->fname",0,0,'L');
            $pdf->Cell(0,5.5,"ID.No.: $stInfo->idnum-$stInfo->idext", 0,1,'R');
            $pdf->Cell(0,3,"",'T',1,'L');
            $pdf->Cell(45,6,"Course No.",1,0,'L');
            $pdf->Cell(120,6,"Descriptive Title",1,0,'C');
            $pdf->Cell(15,6,"Grade",1,0,'C');
            $pdf->Cell(0,6,"Unit/s",1,1,'C');
            $pdf->Ln(2);
            $rowCount = 0;
        }

        $pdf->renderSem($sem, $rows);
        $rowCount += $incoming_rows;
    }



    $pdf->Output();
}
