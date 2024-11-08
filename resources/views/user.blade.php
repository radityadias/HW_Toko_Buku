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
    <x-navbar-user />
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>

    <div class="lg:container w-full h-auto mx-auto mt-[50px] px-4 py-8">
        <!-- Search Bar -->
        
            <form action="{{ route('books.search', '') }}" method="GET" class="flex justify-center mb-6" onsubmit="this.action='{{ url('/search') }}/'+ encodeURIComponent(this.title.value)">
                <input name="title" type="text" placeholder="Search for books..."
                    class="w-3/4 px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="searchInput"  />
                <button type="submit"
                    class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Search
                </button>
            </form>

        
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            @foreach ($books as $book)
                <!-- Card 1 -->
                <div class="bg-white shadow-md rounded-lg p-4 lg:w-[300px] md:w-[250px] w-[230px]">
                    <img src="book-sample.png" alt="Cover Buku" class="w-full h-64 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold mb-1">{{ $book->title }}</h3>
                    <hr>
                    <p class="text-gray-700 mt-2">{{ $book->author }}</p>
                    <p class="text-sm text-gray-500">{{ $book->category->name }}</p>
                    <div class="flex items-center justify-between">
                        <span class="md:text-lg text-[16px] font-semibold">Rp{{ $book->price }}</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Buy now</button>
                    </div>
                </div>
                <!-- Card 1 -->
            @endforeach

        </div>
    </div>
</body>

</html>
