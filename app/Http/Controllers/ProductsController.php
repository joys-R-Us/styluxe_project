<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class ProductsController extends Controller
{

    /**
     * Show items on the public-facing page with filters
     */
    public function publicIndex(Request $request) {
        $query = Products::query();

        // search functionality
        if($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if($request->filled('filter')) {
            switch($request->filter){
                case 'available':
                    $query->where('status', 'Available');
                    break;
                case 'low stock':
                    $query->where('quantity', '<=', 20);  
                    break;
                case 'sold out':
                    $query->where('status', 'Sold Out');
                    break;
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $items = $query->paginate(20);
        $categories = Category::all();

        return view('styluxe.items.index-public', compact('items', 'categories'));
    }

    /**
     * Dashboard view with analytics
     */
    public function dashboard() { // Admin dashboard view
        $user = Auth::user();
        
        // Base query
        $query = Products::query();

        // Analytics data
        $stats = [
            'total_items' => $query->count(),
            'available_items' => (clone $query)->where('status', 'Available')->count(),
            'low_stock_items' => (clone $query)->where('quantity', '<=', 10)->count(),
            'sold_out_items' => (clone $query)->where('status', 'Sold Out')->count(),
            'out_of_stock_items' => (clone $query)->where('status', 'Out-Of-Stock')->count(),
        ];

        // Category breakdown
        $categoryStats = Products::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('
                COALESCE(categories.name, "Uncategorized") as category_name,
                COUNT(products.id) as count,
                SUM(products.quantity) as total_qty
            ')
            ->groupBy('categories.name')
            ->get();

        // Recent activity (last 10 items added/updated)
        $recentItems = (clone $query)
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Low stock alerts
        $lowStockAlerts = (clone $query)
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('status', '!=', 'Sold Out')
            ->get();

        return view('styluxe.dashboard', compact(
            'stats', 
            'categoryStats', 
            'recentItems', 
            'lowStockAlerts'
        ));
    }

    /**
     * show form to create new item
     */
    public function create() {
        $categories = Products::distinct()->pluck('category_id');
        return view('styluxe.items.create', compact('categories'));
    }

    /**
     * store new item
     */
    public function store(Request $request) {
        $validated = $request->validate([
            
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:New,Pre-Loved,Vintage,Branded',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Out-Of-Stock,Sold Out',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        // handle image upload
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('images', 'public');
        }

        // auto generate barcode
        $validated['barcode'] = Products::generateBarcode();

        // track who added the item
        $validated['added_by'] = Auth::id();

        //set defaults
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 20;

        $item = Products::create($validated);

        $this->logStockChange($item, 'added', $item->quantity, 0, $item->quantity);

        return redirect()->route('styluxe.items.show', $item->barcode)->with('success', '✅ Item added successfully!');
    }

    /**
     * show single item details
     */
    public function show($barcode) { 
        $item = Products::where('barcode', $barcode)->first(); // Find by barcode
        if (! $item) {
            return redirect()->route('styluxe.items.index-public')->with('error', 'Item not found.');
        }

        // Only admins and clients exist — clients can view their own orders, items are public

        // get stock history for this item
        $stockHistory = DB::table('stock_logs')
            ->where('product_barcode', $barcode)->latest()->take(10)->get();

        return view('styluxe.items.show', compact('item', 'stockHistory'));
    }

    /**
     * show edit form
     */
    public function edit($barcode) { 
        $item = Products::where('barcode', $barcode)->first();
        if (! $item) {
            return redirect()->route('styluxe.items.index-public')->with('error', 'Item not found.');
        }


        $item = Products::where('barcode', $barcode)->firstOrFail();
        $categories = Category::all();

        return view('styluxe.items.edit', compact('item', 'categories'));
    }

    /**
     * update item
     */
    public function update(Request $request, $barcode) { 
        $item = Products::where('barcode', $barcode)->first();
        if (! $item) {
            return redirect()->route('styluxe.items.index-public')->with('error', 'Item not found.');
        }


        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'condition' => 'required|in:New,Pre-Loved,Vintage,Branded',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Out-Of-Stock,Sold Out',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        // Track quantity change
        $oldQuantity = $item->quantity;
        $newQuantity = $validated['quantity'];

        //image upload
        if ($request->hasFile('image_path')) {
            // Delete old image
            if($item->image_path){
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('images', 'public');
        }

        $item->update($validated);

        // Log stock change if quantity changed
        if ($oldQuantity != $newQuantity) {
            $changeType = $newQuantity > $oldQuantity ? 'added' : 'adjusted';
            $this->logStockChange($item, $changeType, abs($newQuantity - $oldQuantity), $oldQuantity, $newQuantity);
        }
        return redirect()->route('styluxe.items.show', $item->barcode)->with('success', '✅ Item updated successfully.');
    }

    /**
     * delete item
     */
    public function destroy($barcode) { 
        $item = Products::where('barcode', $barcode)->firstOrFail();
        
        // only admin can delete
        if(Auth::user()->role !== 'admin') {
            return redirect()->route('styluxe.unauthorized');
        }

        // Delete associated image
        if($item->image_path){
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();

        return redirect()->route('styluxe.items.index-public')->with('success', '🗑️ Item deleted successfully.');
    }

    /**
     * Show batch upload form
     */
    public function showBatchUploadForm()
    {
        return view('styluxe.batch-upload');
    }

    /**
     * Batch upload via csv
     */
    public function batchUpload(Request $request) {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        $imported = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                $item = array_combine($header, $row);

                Products::create([
                    'barcode' => Products::generateBarcode(),
                    'item_name' => $item['item_name'],
                    'category_id' => 'required|exists:categories,id',
                    'size' => $item['size'],
                    'color' => $item['color'] ?? null,
                    'condition' => $item['condition'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'status' => $item['status'] ?? 'Available',
                    'added_by' => Auth::id(),
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row error: " . $e->getMessage();
            }
        }
        
        if ($errors) {
            return back()->with('warning', "Imported $imported items. Some errors: " . implode('; ', array_slice($errors, 0, 3)));
        }
        
        return redirect()->route('styluxe.dashboard')->with('success', "✅ {$imported} items imported successfully!");
    }

    /**
     * Helper: log stock changes
     */
    private function logStockChange($item, $changeType, $quantityChange, $previousQty, $newQty) {
        DB::table('stock_logs')->insert([
            'product_barcode' => $item->barcode,
            'user_id' => Auth::id(),
            'change_type' => $changeType,
            'quantity_change' => $quantityChange,
            'previous_quantity' => $previousQty,
            'new_quantity' => $newQty,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
