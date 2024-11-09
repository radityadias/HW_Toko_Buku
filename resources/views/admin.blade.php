<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Toko Buku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    {{-- Navbar & Sidebar --}}
    <x-navbar />

    {{-- Konten --}}
    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14 border">
            @section('content')
            {{-- Display Error Message --}}
        @if(session('error'))
        <div id="toast-warning" class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 absolute z-50 left-1/2" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
                <span class="sr-only">Warning icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">{{session('error')}}</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
        @endif
            <p class="text-lg font-semibold">Books</p>
            <x-books-table :books="$books" :categories="$cate" />
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border">
            <p class="text-lg font-semibold">Categories</p>
            <x-categories-table :books="$books" :categories="$cate" />
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14 border">
            <p class="text-lg font-semibold">Log Sale</p>
            <x-sales-table :books="$books" :categories="$cate"/>
        </div>
    </div>

    @error('price')
    <div class="error">{{ $message }}</div>
    @enderror

    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="{{ asset('js/numberFormat.js') }}"></script>
</body>

</html>
