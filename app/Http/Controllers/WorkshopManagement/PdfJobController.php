<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;

class PdfJobController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf('P', 'mm', 'A4'); //use new class;
    }

    // Print Function Starts from the Web Routes
    public function index(): void
    {
        /*-----------------------------------------VARIABLES AND DATA------------------------------------------------*/
        $assignments = [
            'jobInstruction' => 'The CONCAT() function returns a string whose character set depends',
            'meter_number' => 6666,
            'customer_name' => 'Customer',
            'address' => 'Address',
            'tarrif' => 'Tarrif',
            'documentFileName' => 'JobCard'
        ];

        $workShopSupervisor = 'PETER K. NYIRENDA';
        $jobTitle = "SENIOR MANAGER-INVESTMENT OPERATIONS";
        $filepath = public_path() . '/img/ZESCO_removebg.png';
        $this->fpdf->SetAuthor('ZESCO FLEET MASTER');

        $this->fpdf->SetTitle($assignments['documentFileName']);
        $this->fpdf->AliasNbPages('{pages}');

        $this->fpdf->SetAutoPageBreak(true, 15);
        $this->fpdf->AddPage();// Add new pages


        $this->fpdf->Cell(40, 25,
            $this->fpdf->Image(
                $filepath,
                $this->fpdf->GetX(),
                $this->fpdf->GetY(),
                46),
            1, 0, 'L',
            false);
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->Cell(90, 25, 'MECHANICAL WORKSHOP JOB CARD', 1, '', 'C');
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->MultiCell(60, 8.33, "Doc Number:\nCO.14900.FORM.0051\nVersion 1",
            1, "C");
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(70, 25, "WORKSHOP LOCATION", 1, 0, 'T', false);
        $this->fpdf->Cell(50, 25, "DEPARTMENT", 1, 0, 'T', false);
        $this->fpdf->MultiCell(70, 25, "JOB CARD No.", 1, 'T', '', false);

        $this->fpdf->Cell(50, 25, "Date And Time", 1, 0, 'T', false);
        $this->fpdf->Cell(40, 25, "Fleet No./Reg. No.", 1, 0, '', false);
        $this->fpdf->Cell(50, 25, "Make/Model", 1, 0, '', false);
        $this->fpdf->Cell(50, 25, "Chassis No.", 1, 0, '', false);
        $this->fpdf->Ln();
        $this->fpdf->Cell(40, 20, "Kilometers", 1, 0, 'T', false);
        $this->fpdf->Cell(40, 20, "Empty/Loaded", 1, 0, '', false);
        $this->fpdf->Cell(35, 20, "Due for Service", 1, 0, '', false);
        $this->fpdf->Cell(25, 20, "A", 1, 0, 'C', false);
        $this->fpdf->Cell(25, 20, "B", 1, 0, 'C', false);
        $this->fpdf->Cell(25, 20, "C", 1, 0, 'C', false);

        //invoice contents
        $this->fpdf->SetFillColor(28, 153, 85);
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetTextColor(255, 255, 255);
        $this->fpdf->Ln(22);

        $this->fpdf->SetFillColor(224, 235, 255);
        $this->fpdf->SetTextColor(32, 16, 8);
        $this->fpdf->SetFont('Arial', '', 12);

        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->Cell(60, 5, "JOB INSTRUCTION:");
        $this->fpdf->Ln();
        $this->fpdf->MultiCell(190, 5, $assignments['jobInstruction'], 1, '');
        $this->fpdf->MultiCell(190, 8, '', 1, '');
        $this->fpdf->MultiCell(190, 8, '', 1, '');
        $this->fpdf->MultiCell(190, 8, '', 1, '');

        $this->fpdf->Ln();
        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->Cell(60, 5, "WORK DONE AND WORK COMPLETED:");
        $this->fpdf->Ln();
        $this->fpdf->MultiCell(0, 8, '', 1, 0);
        $this->fpdf->MultiCell(190, 8, '', 1, 0);
        $this->fpdf->MultiCell(190, 8, '', 1, 0);
        $this->fpdf->MultiCell(190, 8, '', 1, 0);
        $this->fpdf->MultiCell(190, 8, '', 1, 0);


        $this->fpdf->Cell(95, 25, "Supervisor's Signature", 1, 0, 'T', false);
        $this->fpdf->Cell(95, 25, "Mechanic's Staff Names", 1, 0, 'T', false);
        $this->fpdf->Ln();
        $this->fpdf->Cell(95, 25, "Date/Time Released", 1, 0, 'T', false);
        $this->fpdf->Cell(95, 25, "Date/Time Released", 1, 0, 'T', false);
        $this->fpdf->Ln();
        $this->fpdf->Cell(95, 30, "Driver's Name:", 1, 0, 'T', false);
        $this->fpdf->MultiCell(95, 5,
            "W.SMG' Signature \nOdometer Reading ...................................
                \nRoad Test Remarks ...................................
                \n......................................................................", 1, 0, false);
        $this->fpdf->Cell(95, 25, "Man No.:", 1, 0, 'T', false);
        $this->fpdf->MultiCell(95, 8, "W.SMG' Signature\n............................................",
            1, 0, false);

        $this->fpdf->Ln();
        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->Cell(50, 5, $workShopSupervisor);
        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', '12');
        $this->fpdf->Cell(50, 5, $jobTitle);
        $this->fpdf->Ln();

        $fileName = "JobCard" . ".pdf";
        $this->fpdf->Output('', $fileName);
    }

}

