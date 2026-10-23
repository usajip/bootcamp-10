<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->summaryData();
        $chartOrderDiagramData = $this->chartOrderDiagramData();
        $orderStatusDiagramData = $this->orderStatusDiagramData();
        $latestOrdersData = $this->latestOrdersData();

        return view('admin.dashboard', 
            compact(
                'data',
                'chartOrderDiagramData',
                'orderStatusDiagramData',
                'latestOrdersData'
            )
        );
    }

    private function summaryData()
    {
        return [
            [
                'name'=>'Products',
                'value'=> Product::count(), 
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1f1f1f" viewBox="0 -960 960 960"><path d="M440-183v-274L200-596v274zm80 0 240-139v-274L520-457zm-80 92L160-252q-19-11-29.5-29T120-321v-318q0-22 10.5-40t29.5-29l280-161q19-11 40-11t40 11l280 161q19 11 29.5 29t10.5 40v318q0 22-10.5 40T800-252L520-91q-19 11-40 11t-40-11m200-528 77-44-237-137-78 45zm-160 93 78-45-237-137-78 45z"/></svg>'
            ], 
            [
                'name'=>'Categories',
                'value'=> ProductCategory::count(),
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1f1f1f" viewBox="0 -960 960 960"><path d="m297-581 149-243q6-10 15-14.5t19-4.5 19 4.5 15 14.5l149 243q6 10 6 21t-5 20-14 14.5-21 5.5H331q-12 0-21-5.5T296-540t-5-20 6-21M700-80q-75 0-127.5-52.5T520-260t52.5-127.5T700-440t127.5 52.5T880-260t-52.5 127.5T700-80m-580-60v-240q0-17 11.5-28.5T160-420h240q17 0 28.5 11.5T440-380v240q0 17-11.5 28.5T400-100H160q-17 0-28.5-11.5T120-140m580-20q42 0 71-29t29-71-29-71-71-29-71 29-29 71 29 71 71 29m-500-20h160v-160H200zm202-420h156l-78-126zm298 340"/></svg>'
            ],
            [
                'name'=>'Users',
                'value'=> User::count(),
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1f1f1f" viewBox="0 -960 960 960"><path d="M40-240q-17 0-28.5-11.5T0-280v-23q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65zm240 0q-17 0-28.5-11.5T240-280v-25q0-32 17.5-58.5T307-410t76.5-30 96.5-10q53 0 97.5 10t76.5 30 49 46.5 17 58.5v25q0 17-11.5 28.5T680-240zm500 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v23q0 17-11.5 28.5T920-240zm-455-80h311q-10-20-55.5-35T480-370t-100.5 15-54.5 35M160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440m640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440m-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480m0-80q17 0 28.5-11.5T520-600t-11.5-28.5T480-640t-28.5 11.5T440-600t11.5 28.5T480-560m0-40"/></svg>'
            ],
            [
                'name'=>'Product Clicks',
                'value'=>150,
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1f1f1f" viewBox="0 -960 960 960"><path d="M120-560h40q17 0 28.5 11.5T200-520t-11.5 28.5T160-480h-40q-17 0-28.5-11.5T80-520t11.5-28.5T120-560m68 216 28-28q12-12 28-11.5t28 11.5q12 12 12.5 28.5T273-315l-28 28q-12 12-28.5 11.5T188-288q-11-12-11.5-28t11.5-28m28-324-28-28q-12-12-11.5-28t11.5-28q12-12 28.5-12.5T245-753l28 28q12 12 11.5 28.5T272-668q-12 11-28 11.5T216-668m476 480L530-350l-30 90q-2 7-7.5 10.5T481-246t-11.5-4-7.5-11l-86-286q-2-8 .5-16t7.5-13 13-7.5 16-.5l288 86q7 2 10.5 7.5T715-479t-3 11.5-10 7.5l-90 32 160 160q12 12 12 28t-12 28l-24 24q-12 12-28 12t-28-12M400-760v-40q0-17 11.5-28.5T440-840t28.5 11.5T480-800v40q0 17-11.5 28.5T440-720t-28.5-11.5T400-760m207 35 29-29q11-11 27.5-11.5T692-754q11 11 11.5 27.5T693-698l-29 30q-11 12-27.5 11.5T608-668q-12-12-12.5-28.5T607-725"/></svg>'
            ],
            [
                'name'=>'Revenue',
                'value'=>'Rp' . number_format(5000, 0, ',', '.'),
                'icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#1f1f1f" viewBox="0 -960 960 960"><path d="M560-440q-50 0-85-35t-35-85 35-85 85-35 85 35 35 85-35 85-85 35M280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400m400 240H120q-33 0-56.5-23.5T40-240v-400q0-17 11.5-28.5T80-680t28.5 11.5T120-640v400h640q17 0 28.5 11.5T800-200t-11.5 28.5T760-160M280-400v-320z"/></svg>'
            ]
        ];
    }

    private function chartOrderDiagramData()
    {
        // this function will return the data (total order and revenue) for the order (daily: 7 days) diagram chart with chart.js
        return [
            'labels' => [
                now()->subDays(6)->format('d M'),
                now()->subDays(5)->format('d M'),
                now()->subDays(4)->format('d M'),
                now()->subDays(3)->format('d M'),
                now()->subDays(2)->format('d M'),
                now()->subDays(1)->format('d M'),
                now()->format('d M'),
            ],
            'data' => [
                'orders' => [
                    rand(10, 100),
                    rand(10, 100),
                    rand(10, 100),
                    rand(10, 100),
                    rand(10, 100),
                    rand(10, 100),
                    rand(10, 100),
                ],
                'revenue' => [
                    rand(1000, 5000),
                    rand(1000, 5000),
                    rand(1000, 5000),
                    rand(1000, 5000),
                    rand(1000, 5000),
                    rand(1000, 5000),
                    rand(1000, 5000),
                ]
            ]
        ];
    }

    private function orderStatusDiagramData()
    {
        // this function will return the data for the order status diagram chart with chart.js
        return [
            'labels' => ['Pending', 'Processing', 'Completed', 'Cancelled'],
            'data' => [
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
                rand(10, 100),
            ]
        ];
    }

    private function latestOrdersData()
    {
        return [
            [
                'id' => 1,
                'order_number'=>'INV-'.rand(1000, 9999),
                'customer' => 'John Doe',
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'total_amount' => rand(100000, 5000000),
                'created_at' => now()->subDays(rand(0, 6))->format('d M Y | H:i'),
            ],
            [
                'id' => 2,
                'order_number'=>'INV-'.rand(1000, 9999),
                'customer' => 'Jane Smith',
                'status' => 'Processing',
                'payment_status' => 'Paid',
                'total_amount' => rand(100000, 5000000),
                'created_at' => now()->subDays(rand(0, 6))->format('d M Y | H:i'),
            ],
            [
                'id' => 3,
                'order_number'=>'INV-'.rand(1000, 9999),
                'customer' => 'Alice Johnson',
                'status' => 'Completed',
                'payment_status' => 'Paid',
                'total_amount' => rand(100000, 5000000),
                'created_at' => now()->subDays(rand(0, 6))->format('d M Y | H:i'),
            ],
            [
                'id' => 4,
                'order_number'=>'INV-'.rand(1000, 9999),
                'customer' => 'Bob Williams',
                'status' => 'Cancelled',
                'payment_status' => 'Unpaid',
                'total_amount' => rand(100000, 5000000),
                'created_at' => now()->subDays(rand(0, 6))->format('d M Y | H:i'),
            ],
            [
                'id' => 5,
                'order_number'=>'INV-'.rand(1000, 9999),
                'customer' => 'Charlie Brown',
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'total_amount' => rand(100000, 5000000),
                'created_at' => now()->subDays(rand(0, 6))->format('d M Y | H:i'),
            ]
        ];
    }
}
