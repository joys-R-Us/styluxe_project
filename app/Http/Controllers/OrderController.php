<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Products;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('client_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(15);

        return view('styluxe.orders.index', compact('orders'));
    }

    public function create()
    {
        $items = Products::available()->get();
        return view('styluxe.orders.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barcode' => 'required|exists:products,barcode',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItems = [];

            foreach ($validated['items'] as $itemData) {
                $product = Products::where('barcode', $itemData['barcode'])->first();
                if (! $product) {
                    DB::rollBack();
                    return back()->with('error', "Product with barcode {$itemData['barcode']} not found.")->withInput();
                }

                if ($product->quantity < $itemData['quantity']) {
                    return back()->withErrors([
                        'items' => "Insufficient stock for {$product->item_name}. Available: {$product->quantity}"
                    ])->withInput();
                }

                $subtotal = $product->price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_barcode' => $product->barcode,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'client_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);

                $product = Products::where('barcode', $itemData['product_barcode'])->first();
                $oldQty = $product->quantity;
                $newQty = $oldQty - $itemData['quantity'];
                
                $product->update(['quantity' => $newQty]);

                DB::table('stock_logs')->insert([
                    'product_barcode' => $product->barcode,
                    'user_id' => Auth::id(),
                    'change_type' => 'sold',
                    'quantity_change' => $itemData['quantity'],
                    'previous_quantity' => $oldQty,
                    'new_quantity' => $newQty,
                    'notes' => "Order #{$order->order_number}",
                    'created_at' => now(),
                ]);
            }

            $this->notifyAdmins('order_placed', "New order #{$order->order_number} placed", route('styluxe.order-management.show', $order->id));

            DB::commit();

            return redirect()
                ->route('styluxe.orders.show', $order->id)
                ->with('success', '🛍️ Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create order: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('client_id', Auth::id())
            ->with('items.product')
            ->first();

        if (! $order) {
            return redirect()->route('styluxe.orders.index')->with('error', 'Order not found.');
        }

        return view('styluxe.orders.show', compact('order'));
    }

    public function adminIndex()
    {
        $orders = Order::with('client', 'items')
            ->latest()
            ->paginate(20);

        return view('styluxe.order-management.index', compact('orders'));
    }

    public function adminShow($id)
    {
        $order = Order::with('client', 'items.product')->find($id);
        if (! $order) {
            return redirect()->route('styluxe.order-management.index')->with('error', 'Order not found.');
        }
        return view('styluxe.order-management.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'notes' => 'nullable|string',
        ]);

        $order = Order::find($id);
        if (! $order) {
            return back()->with('error', 'Order not found.');
        }
        $order->update($validated);

        Notification::create([
            'user_id' => $order->client_id,
            'type' => 'order_status_updated',
            'title' => 'Order Status Updated',
            'message' => "Your order #{$order->order_number} status is now: {$order->order_status}",
            'action_url' => route('styluxe.orders.show', $order->id),
        ]);

        return back()->with('success', '✅ Order status updated!');
    }

    private function notifyAdmins($type, $message, $url = null)
    {
        $admins = \App\Models\User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => ucfirst(str_replace('_', ' ', $type)),
                'message' => $message,
                'action_url' => $url,
            ]);
        }
    }
}