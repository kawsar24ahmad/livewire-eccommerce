<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class CartPage extends Component
{
    public $cart = [];
    public function loadCart(){
        $this->cart = session()->get('cart', []);
        // dd($this->cart);

    }
    public function removeItem($cartKey){
        if (isset($this->cart[$cartKey])) {
            unset($this->cart[$cartKey]);
            session()->put('cart', $this->cart);
            session()->flash('error', 'Cart removed successfully');
             $this->dispatch('cart-updated');
        }
    }
    public function updateQuantity($cartKey, $quantity){
        if ($quantity < 1) {
           return;
        }

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] = $quantity;
            session()->put('cart', $this->cart);
            session()->flash('success', 'Cart updated successfully');
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }
    public function clearCart(){
        session()->forget('cart');
        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Cart removed');
    }
    #[Computed]
    public function subtotal(){
        return array_sum(array_map(function($item){
            return $item['price'] * $item['quantity'];
        }, $this->cart));
    }
    public function mount(){
        $this->loadCart();
    }
    public function render()
    {
        return view('livewire.cart-page')->layout('components.layouts.frontend');
    }
}
