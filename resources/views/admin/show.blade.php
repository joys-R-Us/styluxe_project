<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('View Clothing Item') }}
            </h2>
        </div>
    </x-slot>

    <!-- Center the card horizontally -->
    <div class="flex justify-center p-6">
        <div class="bg-black p-6 rounded-lg border border-white flex flex-row gap-6" style="width: 1048px;">

            <!-- Left column: Fixed-size Image -->
            <div class="flex justify-center items-center" style="width: 500px;">
                @if($clothingItem->image_path)
                    <img src="{{ Storage::url($clothingItem->image_path) }}" 
                         class="w-80 h-80 rounded object-cover">
                @else
                    <div class="h-80 w-80 bg-gray-600 flex items-center justify-center text-white border border-white">
                        No Image
                    </div>
                @endif
            </div>

            <!-- Right column: Product Info -->
            <div class="flex flex-col justify-between" style="width: 500px;">
                <div>
                    <h3 class="text-3xl font-bold text-white mb-4">{{ $clothingItem->item_name }}</h3>

                    <p class="mt-4 text-white"><strong>Barcode:</strong> {{ $clothingItem->barcode }}</p>
                    <p class="mt-3 text-white"><strong>Category:</strong> {{ $clothingItem->category }}</p>
                    <p class="mt-3 text-white"><strong>Size:</strong> {{ $clothingItem->size }}</p>
                    <p class="mt-3 text-white"><strong>Color:</strong> {{ $clothingItem->color }}</p>
                    <p class="mt-3 text-white"><strong>Condition:</strong> {{ $clothingItem->condition }}</p>
                    <p class="mt-3 text-white"><strong>Description:</strong> {{ $clothingItem->description }}</p>
                    <p class="mt-3 text-white"><strong>Quantity:</strong> {{ $clothingItem->quantity }}</p>
                    <p class="mt-3 text-white"><strong>Price:</strong> ₱{{ number_format($clothingItem->price, 2) }}</p>
                    <p class="mt-3 text-white"><strong>Status:</strong> {{ $clothingItem->status }}</p>
                </div>

                <!-- Buttons at bottom-right -->
                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.edit', $clothingItem->barcode) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>

                    <a href="{{ route('admin.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
