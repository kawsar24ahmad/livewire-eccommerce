<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class ThankYou extends Component
{
    public $order;

    public function mount()
    {
        // Retrieve the order ID flashed to the session
        $orderId = session('completed_order_id');

        // If someone hits this page directly without an order session, kick them out
        if (!$orderId) {
            $this->redirectRoute('home');
            return; // Stop execution
        }

        // Fetch the order with its items to show on the success page
        // Note: Make sure your Order model relationship is named 'items' or 'orderItems'
        $this->order = Order::with('items')->findOrFail($orderId);
    }
    public function render()
    {
        return view('livewire.thank-you')->layout('components.layouts.frontend');
    }
}
