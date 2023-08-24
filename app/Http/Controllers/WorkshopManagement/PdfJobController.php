<?php

namespace App\Http\Controllers\WorkshopManagement;

use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;

class PdfJobController extends Controller
{
    protected $fpdf;
    private $errorMessage;

    public function __construct()
    {
        $this->fpdf = new Fpdf('P', 'mm', 'A4'); //use new class;
    }

    // Print Function Starts from the Web Routes
    public function index()
    {
        /*-----------------------------------------VARIABLES AND DATA------------------------------------------------*/
        $assignments = [
            'jobInstruction' => 'The CONCAT() function returns a string whose character set depends on the character set of the first string argument. The data type of the result string depends on the data types of the two arguments. Oracle will try to convert the result string in a loss-less manner. For example, if you concatenate a CLOB value with an NCLOB value, the data type of the returned string will be NCLOB.',
            'meter_number' => 6666,
            'customer_name' => 'Customer',
            'address' => 'Address',
            'tarrif' => 'Tarrif',
            'documentFileName' => 'JobCard'
        ];

        $workShopSupervisor = 'PETER K. NYIRENDA';
        $jobTitle = "SENIOR MANAGER-INVESTMENT OPERATIONS";
        $workShopLocation = "MALAMBO";
        $filepath = public_path() . '/img/ZESCO_removebg.png';
        $this->fpdf->SetAuthor('ZESCO FLEET MASTER');
        $location = "WORKSHOP LOCATION \n".$workShopLocation;

        $this->fpdf->SetTitle($assignments['documentFileName']);
        $this->fpdf->AliasNbPages('{pages}');

        $this->fpdf->SetAutoPageBreak(true, 15);
        $this->fpdf->AddPage();// Add new pages

        $label_size = '12';

        /*if (file_exists($filepath)) {
            $this->fpdf->Image($filepath, 90, 0, 30);
            // Arial bold 15
            $this->fpdf->SetFont('Arial', 'B', 15);
            // Move to the right
            // $this->fpdf->Cell(80);
            // Line break
            $this->fpdf->Ln(20);
        }*/

        $this->fpdf->Cell(40, 25,
            $this->fpdf->Image($filepath, $this->fpdf->GetX(), $this->fpdf->GetY(), 46),
            1, 0, 'L',
            false);
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(90, 25, 'MECHANICAL WORKSHOP JOB CARD', 1, '', 'C');
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->MultiCell(60, 8.33, "Doc Number:\nCO.14900.FORM.0051\nVersion 1",
            1, "C");
        $this->fpdf->Ln(5);

        $this->fpdf->Cell(90, 25, $this->fpdf->MultiCell(60, 10, $location, 1, 'L',false), 1, '', 'C');
        $this->fpdf->Cell(90, 25, $this->fpdf->MultiCell(40, 10, 'DEPARTMENT', 1, 'L', false), 1, '', 'C');
        $this->fpdf->Cell(90, 25, 'MECHANICAL WORKSHOP JOB CARD', 1, '', 'C');

        ;
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        ;
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->MultiCell(60, 10, "JOB CARD No.:\nCO.14900.FORM.0051",
            1, "L");

        $this->fpdf->Ln(10);
        //invoice contents
        $this->fpdf->SetFillColor(28, 153, 85);
        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetTextColor(255, 255, 255);


        $this->fpdf->SetFillColor(224, 235, 255);
        $this->fpdf->SetTextColor(32, 16, 8);
        $this->fpdf->SetFont('Arial', '', 12);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "JOB INSTRUCTION:");
        $this->fpdf->Ln();
        $this->fpdf->MultiCell(190, 5, $assignments['jobInstruction'], 1, '');
        $this->fpdf->MultiCell(190, 5, '', 1, '');
        $this->fpdf->MultiCell(190, 5, '', 1, '');
        $this->fpdf->MultiCell(190, 5, '', 1, '');

        $this->fpdf->Ln();
        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "WORK DONE AND WORK COMPLETED:");
        $this->fpdf->Ln();
        $this->fpdf->MultiCell(190, 5, '', 1, 0);
        $this->fpdf->MultiCell(190, 5, '', 1, 0);
        $this->fpdf->MultiCell(190, 5, '', 1, 0);
        $this->fpdf->MultiCell(190, 5, '', 1, 0);
        $this->fpdf->MultiCell(190, 5, '', 1, 0);

        /*$this->fpdf->Ln();

        $this->fpdf->Cell(120, 5, 'TOTAL VALUE OF INVOICE (US$)', 1, 0);
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "This Invoice is due on 01 July 2023");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
//        $this->fpdf->Cell(50, 5, '05-2023', 0, 1);
        $this->fpdf->Ln();
        $this->fpdf->Ln();


        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "Account Name:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, 'Zesco Limited', 0, 1);
        $this->fpdf->Ln(2);


        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(30, 5, "Bank Name:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, 'Standard Chartered Bank Zambia', 0, 1);
        $this->fpdf->Ln(2);


        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "Account Number:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, '8700211454200', 0, 1);
        $this->fpdf->Ln(2);


        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "Branch Name:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, 'North End Branch', 0, 1);
        $this->fpdf->Ln(2);


        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "Swift Code:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, 'SCBLZMLX', 0, 1);
        $this->fpdf->Ln(2);*/
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
        $this->ImprovedTable($header, []);

        $this->fpdf->Ln();
        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, $workShopSupervisor);
        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, $jobTitle);
        $this->fpdf->Ln();

        $filename = "JobCard" . ".pdf";
        /*return response()->streamDownload(function () use ($filename) {
            echo $this->fpdf->Output($filename, 'D');
        }, $filename);*/
        $this->fpdf->Output();
    }

    private function ImprovedTable(array $header, array $data): void
    {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for ($i = 0; $i < count($header); $i++)
            $this->fpdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $this->fpdf->Ln();
        // Data
        foreach ($data as $row) {
            $this->fpdf->Cell($w[0], 6, $row[0], 'LR');
            $this->fpdf->Cell($w[1], 6, $row[1], 'LR');
            $this->fpdf->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R');
            $this->fpdf->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R');
            $this->fpdf->Ln();
        }
        // Closing line
        $this->fpdf->Cell(array_sum($w), 0, '', 'T');
    }
}

