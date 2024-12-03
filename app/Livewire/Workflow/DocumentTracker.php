<?php

namespace App\Livewire\Workflow;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DocumentTracker extends Component
{
    public $document_no;

    protected $rules = [
        'document_no' => ['required'],
    ];

    public function render()
    {
        $task = null;
        if($this->document_no){

          $task = DB::selectOne("  SELECT gh.document_no,
         gh.date_document AS grn_gen_date,
         p.invoice_num,
         --p.invoice_amount,
         p.amount_paid,
         p.PAYMENT_STATUS_FLAG
    FROM invoices_paid_view p, goods_receipt_header gh
   WHERE p.invoice_num = gh.USER_DOCUMENT_NO AND p.invoice_num LIKE 'C0%'
	 AND gh.document_no = '{$this->document_no}'
ORDER BY gh.date_document ASC");
        }

        return view('livewire.workflow.document-tracker',compact('task'));
    }

    public function search() {
        $this->validate();
    }
}
