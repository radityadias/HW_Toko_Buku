<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Toko Buku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FCF8F5] bg-[url('../../public/bg-user.png')] bg-cover bg-center">

    {{-- Navbar --}}
    <x-navbar-user />
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>

    <div class="lg:container w-full h-auto mx-auto mt-[50px] px-4 py-8">
        
        <!-- Search Bar -->
        
        {{-- Mencari data buku dari TransacationsController --}}
        <form action="{{ route('books.search', '') }}" method="GET" class="flex justify-center mb-6" 
            onsubmit="this.action='{{ url('/search') }}/'+ encodeURIComponent(this.title.value)">
            <input name="title" type="text" placeholder="Search for books..."
                class="w-3/4 px-4 py-2 border-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                id="searchInput" value="{{ old('title') }}" />
            <button type="submit"
                class="ml-2 px-4 py-2 bg-[#547592] text-white rounded-lg hover:bg-[#415A71] focus:outline-none focus:ring-2 focus:ring-blue-500">
                Search
            </button>
        </form>
         
        {{-- Error Handling jika buku tidak ditemukan --}}
        @if (session('error'))
            <div id="toast-warning"
                class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 absolute z-50 left-1/2 top-2"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
                    </svg>
                    <span class="sr-only">Warning icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('error') }}</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-warning" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Menampilkan data buku dari fungsi getBooks di TransactionController --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            @foreach ($books as $book)
                @if ($book->stock != 0)
                    <!-- Card Book -->
                    <div class="bg-[#ffffff] shadow-md rounded-lg p-4 lg:w-[300px] md:w-[250px] w-[230px]">
                        <img src="{{ asset('book-sample.png') }}" alt="Cover Buku"
                            class="w-full h-64 object-cover rounded-lg mb-4">
                        <h3 class="text-gray-900 font-semibold mb-1">{{ $book->title }}</h3>
                        <hr>
                        <p class="text-gray-800 mt-2">{{ $book->author }}</p>
                        <p class="text-sm text-gray-500">{{ $book->category->name }}</p>
                        <div class="flex items-center justify-between">
                            <span class="md:text-gray-900 text-[16px] font-semibold">Rp
                                {{ number_format($book->price, 0, ',', '.') }}</span>
                            <form>
                                <button type="submit" onclick="successNotification(booksuccessadd, 1000)"
                                    data-id_book="{{ $book->book_id }}" data-title="{{ $book->title }}"
                                    data-price="{{ $book->price }}"
                                    class="add-to-cart bg-[#F56164] text-white px-4 py-2 rounded hover:bg-[#CB5053]">
                                    Buy now</button>
                            </form>
                        </div>
                    </div>
                    <!-- Card Book -->
                @endif
            @endforeach
        </div>
    </div>

    {{-- Fungsi untuk menambahkan buku ke keranjang --}}
    <script src="{{ asset('js/cart.js') }}"></script>

    {{-- Fungsi untuk formatting harga --}}
    <script src="{{ asset('js/numberFormat.js') }}"></script>

    {{-- Fungsi untuk memunculkan notifikasi --}}
    <script src="{{ asset('js/notification.js') }}"></script>

</body>

</html>
