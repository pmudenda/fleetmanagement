<?php

namespace App\Livewire\Reports\Audit;

use App\Exports\ConsolidateSparesReportExport;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentAudit extends Component {
    use WithPagination;

    public $document_no;

    protected $rules = [
        'document_no' => 'nullable|string'
    ];

    public function render() {
        $documents = [];

        if($this->document_no) {
            $documents = DB::select("
SELECT 

    ds.USER_ACT,

    ds.DATE_ACT,

    GT2. DESCRIPTION AS type_document,

    ds.DOCUMENT_NO,

    GT.DESCRIPTION AS STATUS,

    ds.AMOUNT,

    u.SURNAME,

    u.FIRST_NAME,

    u.MIDDLE_NAME,

    p.CODE_POSITION,

    p.DESCRIPTION

FROM 

    DOCUMENT_STATUS ds

JOIN 

    SPMSUSERS u ON ds.USER_ACT = u.USER_ID

JOIN 

    POSITIONS p ON ds.CODE_POSITION = p.CODE_POSITION

JOIN 

   GENERAL_TABLES GT ON gt.ELEMENT_CODE = DS.STATUS

JOIN GENERAL_TABLES  GT2 ON gt2.ELEMENT_CODE = DS.TYPE_DOCUMENT
 
WHERE GT.table_code = 'STA'

      AND GT2.table_code = 'DOC'

    AND ds.DOCUMENT_NO LIKE '{$this->document_no}'
    ORDER BY ds.DATE_ACT");
        }

        return view('livewire.reports.audit.document-audit', compact('documents'));
    }

    public function search() {
        $this->validate();
        $this->resetPage();
    }

}
