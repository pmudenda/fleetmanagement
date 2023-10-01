<?php

namespace App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\View\Component;

class WorkshopWorkflowApprovers extends Component
{
    public $request;

    /**
     * Create a new component instance.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Application|Factory
    {
        return view('components.workshop-workflow-approvers');
    }
}
