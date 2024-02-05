<?php

namespace App\Livewire\Workflow\Alignment;

use App\Models\Security\User;
use App\Models\Workflow\WorkflowTaskDetail;
use App\Models\Workflow\WorkflowTaskHeader;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class UserAlignment extends Component
{
    public $staff_number_from, $staff_number_to;
    public $userFrom, $userTo;

    public $selected_tasks = [];

    public function rules()
    {
        return [
            'staff_number_from' => 'required|exists:sec_users,staff_no',
            'staff_number_to' => 'required|exists:sec_users,staff_no',
            'selected_tasks.*' => ['required']
        ];
    }

    public function render()
    {
        $tasks = [];

        $users = Cache::remember('users', 60 * 60 * 5, function () {
            return User::all();
        });

        if ($this->staff_number_from) {
            $tasks = User::where('SUPERVISOR_CODE', $this->staff_number_from)
                ->get();
        }
        return view('livewire.workflow.alignment.user-alignment', compact('tasks', 'users'));
    }

    public function search_from()
    {
        $this->validateOnly('staff_number_from');
        $this->userFrom = User::where('staff_no', $this->staff_number_from)->first();
    }

    public function search_to()
    {
        $this->validateOnly('staff_number_to');
        $this->userTo = User::where('staff_no', $this->staff_number_to)->first();
    }

    public function assign()
    {
        $this->validate();
        User::whereIn('staff_no', $this->selected_tasks)
        ->where('SUPERVISOR_CODE', $this->staff_number_from)
            ->update(['SUPERVISOR_CODE' => $this->staff_number_to]);
        $this->reset('staff_number_from', 'staff_number_to', 'selected_tasks');
        $this->dispatch('message', 'Users have been aligned successfully');
    }

    public function selectAll()
    {
        $tasks = User::where('SUPERVISOR_CODE', $this->staff_number_from)
            ->get();
        $this->selected_tasks = $tasks->pluck('reference')->values()->all();
    }

    public function deselect()
    {
        $this->selected_tasks = [];
    }
}
