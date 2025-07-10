<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1>Test Detail Order</h1>
            <p>Order #{{ $order->order_number }}</p>
            
            @if($order->payment)
                <p>Payment Status: {{ $order->payment->status }}</p>
            @endif
            
            @foreach($order->orderItems as $item)
                <div>{{ $item->product->name }}</div>
            @endforeach
        </div>
    </div>
</x-app-layout>
