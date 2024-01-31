<?php

namespace App\Livewire\Workflow\Alignment;

use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskHeader;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TaskAlignment extends Component {
    public $staff_number_from, $staff_number_to;
    public $selected_tasks = [];

    public function rules() {
        return [
            'staff_number_to' => 'required',
            'selected_tasks' => ['required']
        ];
    }

    public function render() {
        $tasks = [];

        $users = Cache::remember('users', 60 * 60 * 5, function () {
            return User::all();
        });

        if ($this->staff_number_from) {
            $tasks = WorkflowTaskHeader::where('ASSIGNED_USER', $this->staff_number_from)
                ->whereNull('DATE_ENDED')
                ->get();
        }
        return view('livewire.workflow.alignment.task-alignment', compact('tasks', 'users'));
    }

    public function search() {

    }

    public function assign() {
        $this->validate();
    }
}
