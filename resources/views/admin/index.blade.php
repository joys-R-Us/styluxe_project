<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Clothing Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if (session('error'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Search & Filters -->
            <form method="GET" action="{{ route('admin.index') }}" 
                class="mb-4 flex justify-between items-center flex-wrap gap-3">

                <!-- LEFT: Search Box -->
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Search item name, category, barcode..."
                           value="{{ request('search') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md"
                           style="width: 283.6px;">

                    <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 border border-white">
                        Search
                    </button>
                </div>

                <!-- RIGHT: Category + Size Filters -->
                <div class="flex items-center gap-2">

                    <select name="category" 
                            class="px-3 py-2 border border-gray-300 rounded-md"
                            style="width: 150px;">
                        <option value="">All Categories</option>
                        <option value="Top" {{ request('category')=='Top' ? 'selected':'' }}>Top</option>
                        <option value="Bottom" {{ request('category')=='Bottom' ? 'selected':'' }}>Bottom</option>
                        <option value="Outerwear" {{ request('category')=='Outerwear' ? 'selected':'' }}>Outerwear</option>
                        <option value="Dresses" {{ request('category')=='Dresses' ? 'selected':'' }}>Dresses</option>
                        <option value="Jumpsuits" {{ request('category')=='Jumpsuits' ? 'selected':'' }}>Jumpsuits</option>
                        <option value="Other" {{ request('category')=='Other' ? 'selected':'' }}>Other</option>
                    </select>

                    <select name="size" 
                            class="px-3 py-2 border border-gray-300 rounded-md"
                            style="width: 111px;">
                        <option value="">All Sizes</option>
                        <option value="XS" {{ request('size')=='XS' ? 'selected':'' }}>XS</option>
                        <option value="S" {{ request('size')=='S' ? 'selected':'' }}>S</option>
                        <option value="M" {{ request('size')=='M' ? 'selected':'' }}>M</option>
                        <option value="L" {{ request('size')=='L' ? 'selected':'' }}>L</option>
                        <option value="XL" {{ request('size')=='XL' ? 'selected':'' }}>XL</option>
                        <option value="XXL" {{ request('size')=='XXL' ? 'selected':'' }}>XXL</option>
                        <option value="Other" {{ request('size')=='Other' ? 'selected':'' }}>Other</option>
                    </select>

                </div>
            </form>

            <!-- Clothing Items Table -->
            <div class="mt-6 bg-black overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-black border border-white-200">

                    <table class="w-full text-white border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700">

                                <th class="p-2 text-left w-28">Image</th>
                                <th class="p-2 text-left w-64">Name</th>
                                <th class="p-2 text-left w-24">Size</th>
                                <th class="p-2 text-left w-24">Price</th>
                                <th class="p-2 text-left w-40">Barcode</th>
                                <th class="p-2 text-left w-32">Status</th>
                                <th class="p-2 text-left w-40">Actions</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($clothingItems as $item)
                                <tr class="border-b border-gray-700 items-center justify-center">

                                    <td class="py-2 w-28 flex items-center justify-center">
                                        @if($item->image_path)
                                            <img src="{{ Storage::url($item->image_path) }}"
                                                 class="h-20 w-20 object-cover rounded">
                                        @else
                                            <div class="h-20 w-20 bg-gray-600 flex items-center justify-center text-gray-300">
                                                No Image
                                            </div>
                                        @endif
                                    </td>

                                    <td class="w-64 text-center">{{ $item->item_name }}</td>
                                    <td class="w-24 text-center">{{ $item->size }}</td>
                                    <td class="w-24 text-center">₱{{ $item->price }}</td>
                                    <td class="w-40 text-center">{{ $item->barcode }}</td>

                                    <!-- Status -->
                                    <td class="w-32 justify-center text-center">
                                        @if($item->status == 'Available')
                                            <span class="px-2 py-1 rounded bg-green-600 text-white">{{ $item->status }}</span>
                                        @elseif($item->status == 'Out-Of-Stock')
                                            <span class="px-2 py-1 rounded bg-yellow-400 text-black">{{ $item->status }}</span>
                                        @elseif($item->status == 'Sold Out')
                                            <span class="px-2 py-1 rounded bg-red-600 text-white">{{ $item->status }}</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-500 text-white">{{ $item->status }}</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="w-40 space-x-2 justify-center text-center">
                                        <a href="{{ route('admin.show', $item->barcode) }}"
                                           class="text-blue-600 px-2 py-1 rounded hover:bg-blue-800">
                                            View
                                        </a>

                                        <a href="{{ route('admin.edit', $item->barcode) }}"
                                           class="text-green-600 px-2 py-1 rounded hover:bg-gray-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.destroy', $item->barcode) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 px-2 py-1 rounded hover:bg-gray-800"
                                                    onclick="return confirm('Delete this item?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach

                            @if($clothingItems->isEmpty())
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">
                                        No items found.
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $clothingItems->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
