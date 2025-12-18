<?php

namespace App\Http\Controllers;
use App\Models\Products;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $items = Products::where('status', 'Available')
                        ->latest()
                        ->take(8) // Show 8 items on homepage
                        ->get();

        return view('styluxe.homepage', compact('items'));
    }
}
