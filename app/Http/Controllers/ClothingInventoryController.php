<?php

namespace App\Http\Controllers;

use App\Models\ClothingInventory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\LoginRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClothingInventoryController extends Controller
{
    public function publicHomepage() {
        $items = ClothingInventory::latest()->take(8)->get();
        return view('styluxe.homepage', compact('items'));
    }

    public function publicIndex(Request $request) {
        $query = ClothingInventory::query();

        if($request->search) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        $items = $query->paginate(20);
        return view('styluxe.items.index-public', compact('items'));
    }

    public function index() {  // Homepage, public page
        $items = ClothingInventory::all(); // all() -- Retrieve all clothing items; paginate(10) for pagination of 10 items per page
        return view('styluxe.homepage', compact('items')); //change ni
    }

    public function dashboard() { // Admin/Manager/Staff dashboard view
        $items = ClothingInventory::all();
        return view('styluxe.dashboard', compact('items'));
    }

    public function create() { // Show the form to create a new clothing item
        return view('styluxe.items.create');
    }

    public function store(Request $request): RedirectResponse { // Handle the form submission to store a new clothing item
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:New,Pre-Loved,Vintage,Branded',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Out-Of-Stock,Sold Out',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('uploads', 'public');
        }

        // generate barcode
        $validated['barcode'] = ClothingInventory::generateBarcode();

        // Use the validated data for creation to avoid accidental mass-assignment of extra fields
        ClothingInventory::create($validated);
        return redirect()->route('styluxe.items.index')->with('success', 'Item added successfully.');
    }

    public function show($barcode) { // View details of a single clothing item
        $item = ClothingInventory::where('barcode', $barcode)->firstOrFail(); // Find by barcode
        return view('styluxe.items.show', compact('item'));
    }

    public function edit($barcode) { // Show the form to edit a specific clothing item
        $item = ClothingInventory::where('barcode', $barcode)->firstOrFail();
        return view('styluxe.items.edit', compact('item'));
    }

    public function update(Request $request, $barcode) { // Handle the form submission to update a specific clothing item
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:New,Pre-Loved,Vintage,Branded',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Out-Of-Stock,Sold Out',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            //'reorder_level' => 'nullable|integer|min:0',
            //'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $item = ClothingInventory::where('barcode', $barcode)->firstOrFail();

        if ($request->hasFile('image_path')) {
            // Delete old image
            if($item->image_path){
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('uploads', 'public');
        }

        $item->update($validated);

        return redirect()->route('styluxe.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($barcode) { // Delete a specific clothing item
        $item = ClothingInventory::where('barcode', $barcode)->firstOrFail();
        // Delete associated image
        if($item->image_path){
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();

        return redirect()->route('styluxe.items.index')->with('success', 'Item deleted successfully.');
    }
}
