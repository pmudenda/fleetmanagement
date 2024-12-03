<?php

namespace App\Livewire\Workflow;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use function Laravel\Prompts\select;

class TaskFollowUpModal extends Component {
    public $document_no;

    protected $rules = [
        'document_no' => ['required'],
    ];

    public function render() {
        $task = null;
        if ($this->document_no) {
            $task = DB::selectOne("SELECT
	t.DATE_GENERATION,
	t.CODE_IDENTIFICATION,
	t.description,
	u.user_id,
	u.first_name || ' ' || u.surname AS User_Responsible,
	u.DESCRIPTION_JOB,
	t.code_position 
FROM
	tasks t LEFT OUTER
	JOIN spmsusers u ON t.code_position = u.code_position 
WHERE
	( SUBSTR( t.CODE_IDENTIFICATION, 5 ) IN ( '{$this->document_no}' ) AND t.status IN ( '01', '22' ) ) 
	OR ( t.CODE_IDENTIFICATION LIKE '%{$this->document_no}%' AND t.status IN ( '01', '22' ) )");
        }
        return view('livewire.workflow.task-follow-up-modal', compact('task'));
    }

    public function search() {
        $this->validate();
    }
}
