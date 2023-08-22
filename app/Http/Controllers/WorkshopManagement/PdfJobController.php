<?php

namespace App\Http\Controllers\WorkshopManagement;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
//

class PdfJobController extends Controller
{
    public function index_old()
    {
        $this->fpdf->SetFont('Arial', 'B', 15);
        $this->fpdf->AddPage("L", ['100', '100']);
        //$this->fpdf->Text(10, 10, "Cintent");

        // Column widths
        $w = array(40, 35, 40, 45);
        $header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');

        $data = [
            ['Zambia','Lusaka',50,18000000]
        ];
        // Header
        for($i=0;$i<count($header);$i++)
            $this->fpdf->Cell($w[$i],7,$header[$i],1,0,'C');
        $this->fpdf->Ln();
        // Data
        foreach($data as $row)
        {
            $this->fpdf->Cell($w[0],6,$row[0],'LR');
            $this->fpdf->Cell($w[1],6,$row[1],'LR');
            $this->fpdf->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
            $this->fpdf->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
            $this->fpdf->Ln();
        }
        // Closing line
        $this->fpdf->Cell(array_sum($w),0,'','T');

        $this->fpdf->Output();

        exit;
    }

    protected $fpdf;
    private $errorMessage;

    public function __construct()
    {
        $this->fpdf = new Fpdf('P', 'mm', 'A4'); //use new class;
    }

    // Print Function Starts from the Web Routes
    public function index()
    {
        // read customer info from data store
        $customer = [
            'service_no'=> 333333,
            'meter_number' => 6666,
            'customer_name' => 'Customer',
            'address' => 'Address',
            'tarrif' => 'Tarrif'
        ];
        //self::getCustomerDetails($id); // Get Customer Details for the Statement
        $service_no = $customer["service_no"];
        $meter_number = $customer["meter_number"];
        $customer_name = $customer["customer_name"];
        $address = $customer["address"];
        $tariff_name = $customer["tarrif"];

        $bill_details = ['data'=> []];// $this->getStatements($service_no);

        $filepath = public_path() . 'assets/dist/img/zesco_logo.png'; //Image for Statement and Invoice

        $this->fpdf->SetAuthor('ZESCO FLEET MASTER');
        $this->fpdf->SetTitle('Export');
        $this->fpdf->AliasNbPages('{pages}');//define new alias for total page numbers

        $this->fpdf->SetAutoPageBreak(true, 15);
        $this->fpdf->AddPage();// Add new pages

        $label_size = '12';
        $text_size = '12';

        // set font to arial, regular, 12pt
        $this->fpdf->SetFont('Arial', '', 12);
        // ----------------------------------------------------

        if (file_exists($filepath)) {
            $this->fpdf->Image($filepath, 90, 0, 30);
            // Arial bold 15
            $this->fpdf->SetFont('Arial', 'B', 15);
            // Move to the right
            // $this->fpdf->Cell(80);
            // Line break
            $this->fpdf->Ln(20);
        }

        /* $this->fpdf->Cell(130, 5, 'ZESCO LTD', 0, 1);
         $this->fpdf->Cell(130, 5, 'Great East Road, Stand No.6949', 0, 1);
         $this->fpdf->Cell(130, 5, 'Lusaka, Zambia', 0, 1);
         $this->fpdf->Cell(130, 5, '260 211 361111', 0, 1);
         $this->fpdf->Cell(130, 5, '', 0, 0);*/
        $invoicee = 'TANESCO, DAR-ES-SALAAM, TAZANIA';
        $attention = 'CHIEF FINANCIAL OFFICER';
        $supply = 'SUMBAWANGA - TANESCO';

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(30, 5, "INVOICE No.:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, 'INV000000162023', 0, 1);
        $this->fpdf->Ln(2);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(30, 5, "TO:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, $invoicee, 0, 1);
        $this->fpdf->Ln(2);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(30, 5, "ATTN:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, $attention, 0, 1);
        $this->fpdf->Ln(2);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "ELECTRICITY SUPPLY TO:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, $supply, 0, 1);
        $this->fpdf->Ln(2);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "INVOICE FOR MAY 2023");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, '', 0, 1);
        $this->fpdf->Ln(2);

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(30, 5, "DATE:");
        $this->fpdf->SetFont('Arial', '', $text_size);
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(50, 5, '01 JUNE 2023', 0, 1);
        $this->fpdf->Ln(2);

        //        $this->fpdf->Cell(130, 5, 'Great East Road, Stand No.6949', 0, 1);
        //        $this->fpdf->Cell(130, 5, 'Lusaka, Zambia', 0, 1);
        //        $this->fpdf->Cell(130, 5, '260 211 361111', 0, 1);
        //        $this->fpdf->Cell(130, 5, '', 0, 0);
        //----------------------------------------------------------
        //        $this->fpdf->Cell(189, 10, '', 0, 1);//end of line
        //-----------------------------------------------------------
        //----------------------------------------------------------------
        //BOLD AND-------------------------------------------
        //        $this->fpdf->SetFont('Arial', 'B', $label_size);
        //        $this->fpdf->Cell(30, 5, 'Customer:");
        //        $this->fpdf->SetFont('Arial', '', $text_size);
        //        $this->fpdf->SetTextColor(0, 0, 0);
        //        $this->fpdf->Cell(50, 5, $customer_name, 0, 1);
        //-------------------------------------------------------


        //---------------------------------------------------------------
        //BOLD -------------------------------------------
        //        $this->fpdf->SetFont('Arial', 'B', $label_size);
        //END OF BOLD --------------------------------------------
        //        $this->fpdf->Cell(30, 5, "Service No:");
        //
        ////UNSET BOLD--------------------------------------------
        //        $this->fpdf->SetFont('Arial', '', $text_size);
        ////END OF UNSET BOLD--------------------------------------------
        //        $this->fpdf->Cell(55, 5, $service_no, 0, 1);
        //----------------------------------------------------------------


        //----------------------------------------------------------------
        //BOLD -------------------------------------------
        //        $this->fpdf->SetFont('Arial', 'B', $label_size);
        ////END OF BOLD --------------------------------------------
        //        $this->fpdf->Cell(30, 5, "Meter No:");
        //
        ////UNSET BOLD --------------------------------------------
        //        $this->fpdf->SetFont('Arial', '', $text_size);
        ////END OF UNSET BOLD --------------------------------------------
        //        $this->fpdf->Cell(55, 5, $meter_number, 0, 1);
        ////------------------------------------------------------------------

        //---------------------------------------------------------------------
        ////BOLD AND GREEN-------------------------------------------
        //        $this->fpdf->SetFont('Arial', 'B', $label_size);
        ////END OF BOLD AND GREEN--------------------------------------------
        //        $this->fpdf->Cell(30, 5, "Tariff Name:");
        //
        ////UNSET BOLD --------------------------------------------
        //        $this->fpdf->SetFont('Arial', '', $text_size);
        ////END OF UNSET BOLD--------------------------------------------
        //        $this->fpdf->Cell(55, 5, $tariff_name, 0, 1);
        //------------------------------------------------------------------


        //----------------------------------------------------------------------
        //BOLD -------------------------------------------
        //        $this->fpdf->SetFont('Arial', 'B', $label_size);
        //END OF BOLD --------------------------------------------
        //        $this->fpdf->Cell(30, 5, "Address:");

        //UNSET BOLD --------------------------------------------
        //        $this->fpdf->SetFont('Arial', '', $text_size);
        //END OF UNSET BOLD--------------------------------------------
        //        $this->fpdf->Cell(50, 5, $address, 0, 1);
        //--------------------------------------------------------------------


        //make a dummy empty cell as a vertical spacer
        //-------------------------------------------------------------------------------------------------------
                $this->fpdf->Cell(189, 10, '', 0, 1);//end of line
        //-------------------------------------------------------------------------------------------------------

        //invoice contents

                $this->fpdf->SetFillColor(28, 153, 85);
        //        $this->fpdf->SetDrawColor(28, 153, 85);
        //$pdf->SetLineWidth(.3);
                $this->fpdf->SetFont('Arial', 'B', 12);
                $this->fpdf->SetTextColor(255, 255, 255);
        //-------------------------------------------------------------------------------------------------------


        $this->fpdf->SetFillColor(224, 235, 255);
        $this->fpdf->SetTextColor(32, 16, 8);


        $this->fpdf->SetFont('Arial', '', 12);


//        foreach ($bill_details['data'] as $item) {
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "A. DEMAND CHARGES");
        $this->fpdf->Ln();
        $this->fpdf->Cell(120, 5, '1. Contracted Capacity' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'7,672', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------

        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '2. Actual Maximum Demand (Kva)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'11,730', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->Ln();

        $this->fpdf->Cell(120, 5, '3. Multiplication Factor' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'1.00', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------

        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '4. Chargeable Maximum Demand (Kva)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'-', 1, '');//end of line


        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '5. Tariff' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'-', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->Ln();

        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '6. Demand Charge @0.00 US$/Kva' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'-', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(60, 5, "B. ENERGY CHARGES");
        $this->fpdf->Ln();
        $this->fpdf->Cell(120, 5, '1. Present Meter Reading (kWh)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'256,932,280', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------

        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '2. Previous Meter Reading' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'252,057,060', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->Ln();

        $this->fpdf->Cell(120, 5, '3. Units Consumed (kWh)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'4,875,220', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------

        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '4. Multiplication Factor' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'1.00', 1, '');//end of line


        $this->fpdf->Ln();
        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '5. Chargeable Units (kWh)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'4,875,220', 1, '');//end of line
//-------------------------------------------------------------------------------------------------------
        $this->fpdf->Ln();

        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '6. Rate per unit US$/kWh' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'0.1100', 1, '');//end of line

        $this->fpdf->Ln();

        //-------------------------------------------------------------------------------------------------------

        $this->fpdf->Cell(120, 5, '7. Energy Charge' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'536,274.20', 1, '');//end of line


        $this->fpdf->Ln();
        $this->fpdf->Ln();


        $this->fpdf->Cell(120, 5, 'TOTAL VALUE OF INVOICE (US$)' , 1, 0);
//            $this->fpdf->Cell(47, 5, $item['period'], 1, 0);
//            $this->fpdf->Cell(35, 5, "ZMW " . $item['amount'], 1, 0);
//            $this->fpdf->Cell(38, 5, "ZMW " . $item['balance'], 1, 0);
        $this->fpdf->Cell(70, 5,'536,274.20', 1, '');//end of line

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
        $this->fpdf->Ln(2);



        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Ln();
        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "ALLAN MUZENGA");
        $this->fpdf->Ln();
        $this->fpdf->SetFont('Arial', 'B', $label_size);
        $this->fpdf->Cell(50, 5, "SENIOR MANAGER-INVESTMENT OPERATIONS");
        $this->fpdf->Ln();
//        }

        $filename = "Bulk_Billing_Invoice-" . "$customer_name" . ".pdf";
        return response()->streamDownload(function () use ($filename) {
            echo $this->fpdf->Output($filename, 'D');
        }, $filename);
    }
}

