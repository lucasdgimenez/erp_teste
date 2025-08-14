<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CarrinhoComponent extends Component
{
    public $showHeader;
    public $showActions;
    public $containerClass;
    /**
     * Create a new component instance.
     */
    public function __construct($showHeader = true, $showActions = true, $containerClass = '')
    {
        $this->showHeader = $showHeader;
        $this->showActions = $showActions;
        $this->containerClass = $containerClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.carrinho-component');
    }
}
