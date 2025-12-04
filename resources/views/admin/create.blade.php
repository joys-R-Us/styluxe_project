<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<nav>
    @include('admin.navigation')
    
</nav>
<body class="bg bg-white" style="margin: 60px;">
    <header name="header">
        <h2 class="bg bg-white font-semibold text-xl text-black leading-tight">
            {{ __('Add New Clothing Item') }}
        </h2>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border border-blue-200">      
                    
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

                    <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Item Name -->
                        <div>
                            <x-input-label for="item_name" :value="__('Item Name')" />
                            <x-text-input id="item_name" class="block mt-1 w-full" type="text" name="item_name" required autofocus />
                        </div>

                        <!-- Category -->

                        <div class="mt-4">
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="block mt-1 w-full" required>
                                <option value="Top">{{ __('Top') }}</option>
                                <option value="Bottom">{{ __('Bottom') }}</option>
                                <option value="Outerwear">{{ __('Outerwear') }}</option>
                                <option value="Dresses">{{ __('Dress') }}</option>
                                <option value="Jumpsuits">{{ __('Jumpsuits') }}</option>
                                <option value="Other">{{ __('Other')}}</option> <!-- Optional -->
                            </select>
                        </div>

                        <!-- Size -->
                        <div class="mt-4">
                            <x-input-label for="size" :value="__('Size')" />
                            <select id="size" name="size" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="XS">{{ __('XS') }}</option>
                                <option value="S">{{ __('S') }}</option>
                                <option value="M">{{ __('M') }}</option>
                                <option value="L">{{ __('L') }}</option>
                                <option value="XL">{{ __('XL') }}</option>
                                <option value="XXL">{{ __('XXL') }}</option>
                                <option value="Other">{{ __('Other')}}</option> <!-- Optional -->
                            </select>
                        </div>

                        <!-- Color -->
                        <div class="mt-4">
                            <x-input-label for="color" :value="__('Color')" />
                            <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" required/>
                        </div>

                        <!-- Condition -->
                        <div class="mt-4">
                            <x-input-label for="condition" :value="__('Condition')" />
                            <select id="condition" name="condition" class="block mt-1 w-full" required>
                                <option value="New">{{ __('New') }}</option>
                                <option value="Pre-Loved">{{ __('Pre-Loved') }}</option>
                                <option value="Vintage">{{ __('Vintage') }}</option>
                                <option value="Branded">{{ __('Branded') }}</option>
                            </select>                        
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" class="block mt-1 w-full rounded-md shadow-sm" name="description"></textarea>
                            <!-- gi change nako wla nako gi required-->
                        </div>

                        <!-- Quantity -->
                        <div class="mt-4">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" required />
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input placeholder="₱" id="price" class="block mt-1 w-full" type="number" name="price" step="0.01" required />
                        </div>

                        <!-- Image URL -->
                        <div class="mt-4">
                            <x-input-label for="image_path" :value="__('Product Image')" />
                            <input id="image_path" class="block mt-1 w-full text-white" type="file" name="image_path" />
                        </div>

                         
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="Available">{{ __('Available') }}</option>
                                <option value="Sold Out">{{ __('Sold Out') }}</option>
                                <option value="Out-Of-Stock">{{ __('Out-Of-Stock') }}</option>
                            </select>
                        </div> 
                        

                        <!-- 
                        <div class="mt-4">
                            <x-input-label for="barcode" :value="__('Barcode')" />
                            <x-text-input id="barcode" class="block mt-1 w-full" type="text" name="barcode" required />
                        </div>
                        --> 

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Add Item') }}
                            </x-primary-button>
                            <x-secondary-button type="cancel" href="{{ route('admin.index') }}" class="ml-1">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>