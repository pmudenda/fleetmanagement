<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContentHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public string $pageTitle;
    public string $activeCrumb;
    public string $link;
    public string $linkText;

    public function __construct($activeCrumb ='Dashboard v1', $link ='home', $linkText = "Home", $pageTitle = "Dashboard")
    {
        if (empty($pageTitle)) {
            $pageTitle = "";
        }
        $this->pageTitle = $pageTitle;
        $this->activeCrumb = $activeCrumb;
        $this->link = $link;
        if (!empty($linkText)) {
            $this->linkText = $linkText;
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.content-header');
    }
}
