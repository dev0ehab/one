<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Buttons extends Component
{
    public $addbutton ; 
    public $deletebutton ; 
    public $extrabuttons ;

    public function __construct($addbutton = null , $extrabuttons = null , $deletebutton = null )
    {
        $this->addbutton    = $addbutton    ;
        $this->extrabuttons = $extrabuttons ;
        $this->deletebutton = $deletebutton ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.buttons');
    }
}
