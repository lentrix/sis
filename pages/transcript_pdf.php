<?php

session_start();
include("../library/transcript_functions.php");
include("../library/fpdf.php");

$orno = $_GET['orno'];
$ordate = $_GET['ordate'];
$remarks = $_GET['remarks'];
$date = $_GET['date'];

class PDF extends FPDF
{
    function Footer()
    {
        global $orno;
        global $ordate;
        global $remarks;
        global $date;

        $this->SetY(-60);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(105, 10, "OR. No.: $orno", 0, 0, 'L');
        $this->Cell(0, 10, "Date Issued: $ordate", 0, 0, 'R');
        $this->Ln(10);
        $this->Cell(40, 10, "REMARKS:", 0, 0, 'L');
        $this->Cell(0, 10, $remarks, 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(40, 10, "Date Issued:", 0, 0, 'L');
        $this->Cell(0, 10, $date, 0, 0, 'L');
        $this->Ln(10);
        $this->Cell(0, 10, "Not Valid Without", 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(0, 10, "MDC Seal", 0, 0, 'L');
        $this->Ln(10);
        $this->Cell(135, 10, 'Page ' . $this->PageNo() . " of {nb} pages", 0, 0, 'L');
        $this->Cell(0, 10, "JOSE RUEL B. ALAMPAYAN", 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(135, 10, "");
        $this->Cell(0, 10, "Registrar", 0, 0, 'C');
    }

    function firstPage()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 12);
        $this->SetY(65);
        $this->Cell(15, 6, "Name: ", 0, 0, 'L');
        $this->Cell(105, 6, "Name of Student Here", 'B', 1, 'L');

        $this->Cell(45, 6, 'Student Number:', 0, 0, 'L');
        $this->Cell(30, 6, "00000-1T00", 'B', 0, 'L');
        $this->Cell(18, 6, "    Sex:", 0, 0, 'L');
        $this->Cell(27, 6, "Female", 'B', 1, 'L');

        $this->Cell(45, 6, "Date of Birth:", 0, 0, 'L');
        $this->Cell(75, 6, "October 2, 1979", 'B', 1, 'L');

        $this->Cell(45, 6, "Place of Birth:", 0, 0, 'L');
        $this->Cell(75, 6, "Tubigon, Bohol", 'B', 1, 'L');

        $this->Cell(45, 6, "Nationality:", 0, 0, 'L');
        $this->Cell(75, 6, "Filipino", 'B', 1, 'L');

        $this->Cell(45, 6, "Religion:", 0, 0, 'L');
        $this->Cell(75, 6, "Roman Catholic", 'B', 1, 'L');

        $this->Cell(45, 6, "Parent/Guardians:", 0, 0, 'L');
        $this->Cell(75, 6, "Mr. & Mrs. So and So", 'B', 1, 'L');

        $this->Cell(45, 6, "          Address:", 0, 0, 'L');
        $this->Cell(75, 6, "Tubigon, Bohol", 'B', 1, 'L');

        $this->Cell(45, 6, "Home Address:", 0, 0, 'L');
        $this->Cell(75, 6, "Tubigon, Bohol", 'B', 1, 'L');

        $this->Cell(45, 6, "Date Admitted:", 0, 0, 'L');
        $this->Cell(75, 6, "May 25, 2010", 'B', 1, 'L');

        $this->Cell(45, 6, "Entrance Data:", 0, 0, 'L');
        $this->Cell(75, 6, "FORM 138", 'B', 1, 'L');

        $this->Cell(45, 6, "College of:", 0, 0, 'L');
        $this->Cell(75, 6, "Engineering", 'B', 1, 'L');

        $this->Cell(45,6,"Preliminary Education:",0,1,'L');

        $this->Cell(45,6,"",0,0,'L');
        $this->Cell(90,6, "Name of School",1,0,'C');
        $this->Cell(0,6,'School Year',1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Elementary: ",0,0,'L');
        $this->Cell(90,6, "Tubigon West Central Elementary School",1,0,'L');
        $this->Cell(0,6,"2005-2006", 1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Secondary: ",0,0,'L');
        $this->Cell(90,6, "Holy Cross Academy",1,0,'L');
        $this->Cell(0,6,"2009-2010", 1,1,'C');

        $this->Cell(18,6,"");
        $this->Cell(27,6,"Tertiary: ",0,0,'L');
        $this->Cell(90,6, "",1,0,'L');
        $this->Cell(0,6,"", 1,1,'C');

    }
}

if (!isset($_SESSION['user'])) header("location:index.php");

if (isset($_GET['idnum'])) {

    $pdf = new PDF('P', 'mm', [215.9, 330.2]);
    $pdf->AliasNbPages();
    $pdf->firstPage();

    $pdf->Output();
}
