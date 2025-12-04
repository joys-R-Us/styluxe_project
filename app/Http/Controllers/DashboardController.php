<?php

namespace App\Http\Controllers;

use App\Models\ClothingInventory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $items = ClothingInventory::all();
        return view('dashboard.index', compact('items'));
    }
}
