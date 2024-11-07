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
        <div class="p-4 border">
            <x-products-list/>
        </div>
    </div>



    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
</body>

</html>
