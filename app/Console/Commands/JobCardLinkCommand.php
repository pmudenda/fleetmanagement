<?php

namespace App\Console\Commands;

use App\Models\Common\MaterialDetail;
use App\Models\Common\MaterialHeader;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\progress;

class JobCardLinkCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobcard:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate job card link';

    /**
     * Execute the console command.
     */
    public function handle() {

        Log::channel('jobcard')->info('Job Card Link command started, currently checking unlinked Purchase Orders');

        try{
            $pos = DB::select(" SELECT PR.DATE_DOCUMENT,
	PO.PURCHASE_REQUISITION_NO,
	PO.document_no,
	po.status,
	PR.USER_DOCUMENT_NO 
FROM
	purchase_order_header PO,
	purchase_requisition_header PR 
WHERE
	 PO.PURCHASE_REQUISITION_NO = PR.DOCUMENT_NO 
	AND pR.USER_DOCUMENT_NO LIKE 'TMS%' 
	AND po.status = '04' 
ORDER BY
	PR.DATE_DOCUMENT DESC");
        }catch(\Exception $e){
           return Log::channel('jobcard')->error($e->getMessage());
        }

        Log::channel('jobcard')->info('{total} Purchase Orders found, attempting to perform linking',['total' => count($pos)]);

        $this->withProgressBar($pos, function ($po) use ($pos) {

            Log::channel('jobcard')->info('Processing Document No {document_no} for Vehicle {project_no} - {user_document_no}',(array)$po);

            $this->process($po);
        });
    }

    private function process($po) {
        Log::channel('jobcard')->info('Retrieving Header Information');

        $header = DB::selectOne("SELECT
	REF_NO AS req_no,
	reg_no,
	date_send AS valid_date_from,
	date_Act AS created_by,
	date_Act AS updated_at,
	item_type,
	WORKSHOP_NO,
	FORM_ORDER,
	USER_REQUESTING AS requested_by,
	AUTHORISER AS authorized_by,
	COMMENTS,
	ind AS status,
	ITEM_TYPE AS requisition_type,
	USER_REQUESTING AS requested_by_id,
	code_office AS purchase_office,
	SUPPLIER_CODE,
	code_unit AS user_unit,
	user_act 
FROM
	gtamaterials_header 
WHERE
	form_order = '{$po->user_document_no}'");

        if (!$header) {
            return Log::error("No header information found, exiting");
        }

        Log::channel('jobcard')->info('Retrieving Details Information');

        $details = DB::select("SELECT REF_NO as req_no, QUANTITY, MAT_CODE as material_code, unit_issue as unit_of_measure, 
SPECIFICATIONS,  user_act,REG_NO,AMOUNT,PRICE,REF_NO, DATE_MAT as date_created,DATE_MAT as updated_at,
DEF_NO,WSHP_ACT_CODE, SECTION_CODE
FROM gtamaterials_DETAIL
where REF_NO = '{$header->req_no}'");

        foreach ($details as $detail) {
            if (!$detail) {
                return Log::error("No header information found, exiting");
            }

            Log::channel('jobcard')->info('Mapping vehicle information');

//            dd($header);
            $header->reg_no = $detail->reg_no;
//            $header->valid_date_from =  $header->valid_date_from;
            $header->valid_date_to = $header->valid_date_from;
            $header->veh_reg_no = $detail->reg_no;
            $header->status = '04';
            $header->proc_ref = $po->document_no;
            $header->st_pur = $po->document_no;
            $header->is_fuel = 'N';

//            DB::transaction(function () use ($detail, $header) {
            Log::channel('jobcard')->info('Writing information to FMS Header table');

            MaterialHeader::create((array)$header);

            $detail->date_created = Carbon::createFromFormat('Y-m-d H:m:s', $detail->date_created)->format('Y-m-d');
            $detail->updated_at = Carbon::createFromFormat('Y-m-d H:m:s', $detail->updated_at)->format('Y-m-d');
            $detail->created_by = $detail->user_act;
            $detail->claimed = null;

            Log::channel('jobcard')->info('Writing information to FMS Details table');

            MaterialDetail::create((array)$detail);
//            });
        }

        Log::channel('jobcard')->info('Process completed successfully');

    }

}
