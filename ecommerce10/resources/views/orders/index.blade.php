<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Table for Latest Orders --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4 flex-wrap">
                        <h3 class="text-lg font-semibold mb-4">Latest Orders</h3>
                        <a href="{{ route('orders.index') }}" class="text-blue-500 hover:underline">View All Orders</a>
                    </div>
                    @if($orders->isEmpty())
                        <p>No recent orders found.</p>
                    @else
                    <div class="relative overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2">Order ID</th>
                                    <th class="border px-4 py-2">Customer</th>
                                    <th class="border px-4 py-2">Total</th>
                                    <th class="border px-4 py-2">Status</th>
                                    <th class="border px-4 py-2">Payment Status</th>
                                    <th class="border px-4 py-2">Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('orders.show', $order->order_number) }}" class="text-blue-500 hover:underline">{{ $order->order_number }}</a>
                                        </td>
                                        <td class="border px-4 py-2">{{ $order->user->name }}</td>
                                        <td class="border px-4 py-2">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="border px-4 py-2">{{ $order->status }}</td>
                                        <td class="border px-4 py-2">{{ $order->payment_status }}</td>
                                        <td class="border px-4 py-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>