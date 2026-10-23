<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-3">
                @foreach($data as $item)
                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="flex justify-between items-center">
                            <p class="text-md">{{ $item['name'] }}</p>
                            <div>
                                {!! $item['icon'] !!}
                            </div>
                        </div>
                        <div>
                            <p class="text-black text-[30px] font-bold">{{ $item['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-3 mb-3">
                <div class="bg-white overflow-hidden shadow-sm min-w-[400px] sm:min-w-[500px] sm:rounded-lg flex-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Orders (Last 7 Days)</h3>
                        <div class="relative h-80">
                            <canvas id="orderChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg min-w-[400px] sm:min-w-[500px] flex-1">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Order Status</h3>
                        <div class="relative h-80">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Table for Latest Orders --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Latest Orders</h3>
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
                                @foreach($latestOrdersData as $order)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $order['order_number'] }}</td>
                                        <td class="border px-4 py-2">{{ $order['customer'] }}</td>
                                        <td class="border px-4 py-2">Rp{{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                                        <td class="border px-4 py-2">{{ $order['status'] }}</td>
                                        <td class="border px-4 py-2">{{ $order['payment_status'] }}</td>
                                        <td class="border px-4 py-2">{{ $order['created_at'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

    <script type="application/json" id="order-chart-data">@json($chartOrderDiagramData)</script>
    <script type="application/json" id="order-status-chart-data">@json($orderStatusDiagramData)</script>

    @push('scripts')
        @vite('resources/js/dashboard-chart.js')
    @endpush
</x-app-layout>
