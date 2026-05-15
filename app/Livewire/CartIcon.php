<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    public $cartCount = 0;
    #[On('cart-updated')]
    public function updateCartCount(){
        $cart = session()->get('cart',[]);
        $this->cartCount = array_sum(array_column($cart, 'quantity'));
    }
    public function mount(){
        $this->updateCartCount();
    }
    public function render()
    {
        return view('livewire.cart-icon');
    }
}
