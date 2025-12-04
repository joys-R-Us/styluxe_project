<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Clothing Item') }}
        </h2>
    </x-slot>

    <div class="py-10" style="padding: 10px 300px">
        <div class="max-w-4xl mx-auto bg-black p-6 rounded-lg">
            
            @if ($errors->any())
                <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.update', $clothingItem->barcode) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="item_name" :value="'Item Name'" />
                    <x-text-input id="item_name" class="w-full" type="text" name="item_name"
                        value="{{ $clothingItem->item_name }}" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="category" :value="'Category'" />
                    <select name="category" class="w-full">
                        <option value="{{ $clothingItem->category }}">{{ $clothingItem->category }}</option>
                        <option value="Top">Top</option>
                        <option value="Bottom">Bottom</option>
                        <option value="Outerwear">Outerwear</option>
                        <option value="Dresses">Dress</option>
                        <option value="Jumpsuits">Jumpsuits</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="size" :value="'Size'" />
                    <select name="size" class="w-full">
                        <option selected>{{ $clothingItem->size }}</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="color" :value="'Color'" />
                    <x-text-input id="color" class="w-full" type="text"
                        name="color" value="{{ $clothingItem->color }}" />
                </div>

                <div class="mb-4">
                    <x-input-label for="condition" :value="'Condition'" />
                    <select name="condition" class="w-full">
                        <option selected>{{ $clothingItem->condition }}</option>
                        <option value="New">New</option>
                        <option value="Pre-Loved">Pre-Loved</option>
                        <option value="Vintage">Vintage</option>
                        <option value="Branded">Branded</option>
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="description" :value="'Description'" />
                    <textarea name="description" class="w-full">{{ $clothingItem->description }}</textarea>
                </div>

                <div class="mb-4">
                    <x-input-label for="quantity" :value="'Quantity'" />
                    <x-text-input id="quantity" type="number" class="w-full"
                        name="quantity" value="{{ $clothingItem->quantity }}" />
                </div>

                <div class="mb-4">
                    <x-input-label for="price" :value="'Price (₱)'" />
                    <x-text-input id="price" type="number" step="0.01"
                        class="w-full" name="price" value="{{ $clothingItem->price }}" />
                </div>

                <div class="mb-4">
                    <x-input-label for="status" :value="'Status'" />
                    <select name="status" class="w-full">
                        <option selected>{{ $clothingItem->status }}</option>
                        <option value="Available">Available</option>
                        <option value="Sold Out">Sold Out</option>
                        <option value="Out-Of-Stock">Out-Of-Stock</option>
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="image_path" :value="'Product Image'" />
                    <input src="{{  Storage::url($clothingItem->image_path)  }}" type="file" name="image_path" class="text-white">
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-primary-button>Update Item</x-primary-button>

                    <a href="{{ route('admin.index') }}"
                       class="bg-gray-600 px-4 py-2 rounded text-white hover:bg-gray-700">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
