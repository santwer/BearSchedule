<?php

namespace App\View\Components;

use http\Env\Request;
use Illuminate\View\Component;

class MainMenu extends Component
{
    public $projectid = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(?int $projectid = null)
    {
        $this->projectid = $projectid;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $projects = auth()->user()->projects()->get();
        $locals = get_langs();

        return view('components.main-menu', compact('projects', 'locals'));
    }


}
