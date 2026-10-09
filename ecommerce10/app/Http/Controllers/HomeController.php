<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Users data for table view
        $users = [
            ['name' => 'Alice', 'age' => 25, 'address' => '123 Main St'],
            ['name' => 'Bob', 'age' => 30, 'address' => '456 Elm St'],
            ['name' => 'Charlie', 'age' => 35, 'address' => '789 Oak St'],
        ];
        return view('home.index', compact('users'));
        // return view('home.index', ['pengguna' => $users]);
    }

    public function index2()
    {
        return view('home.index2');
    }
}
