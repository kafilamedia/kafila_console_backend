<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $close;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $close)
    {
        $this->id= $id;
        $this->close = $close;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
