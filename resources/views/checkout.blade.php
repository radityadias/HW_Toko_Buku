<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FCF8F5] bg-[url('../../public/bg-user.png')] bg-cover bg-center">

    {{-- Notifikasi jika checkout berhasil dan Error Handling nama tidak dimasukkan --}}
    <div id="toast-success"
        class=" opacity-0 z-50 absolute  transition-opacity right-2 top-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
        role="alert">
        <div
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div id="message-notification"class="ms-3 text-sm font-normal">Item moved successfully.</div>
        <button type="button"
            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
            data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>

    {{-- Menampikan data buku yang telah masuk keranjang --}}
    <div class="container mx-auto px-4 py-8">
        <div class="bg-[#1A2731] shadow-md rounded-lg overflow-hidden">
            @if (isset($cart) && count($cart) > 0)
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-50 dark:text-gray-50">
                        <thead class="text-xs text-black uppercase bg-[#D7C6B1] dark:bg-gray-50 dark:text-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID Buku</th>
                                <th scope="col" class="px-6 py-3">Title</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $disabled = true;
                            @endphp
                            @foreach ($cart as $index => $item)
                                @php
                                    $book = $books->where('book_id', $item['id_book'])->first(); // Mencari data buku sesuai book_id (primari key)
                                @endphp
                                <tr
                                    class="bg-white dark:bg-gray-800 dark:border-[#1A2731] hover:bg-gray-100 dark:hover:bg-gray-600 text-black border-b border-gray-200">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap dark:text-white">
                                        {{ $item['id_book'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item['title'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($book && $item['quantity'] > $book->stock)
                                            @php
                                                $disabled = false;
                                            @endphp
                                            <div class="text-red-500 text-sm">
                                                {{ $item['title'] }} stock is insufficient. <br> available:
                                                {{ $book->stock }}.
                                            </div>
                                        @endif
                                        <div class="inline-flex rounded-md shadow-sm" role="group">

                                            {{-- Button untuk mengurangi quantity --}}
                                            <button type="button"
                                                class="px-4 py-2 text-sm font-medium text-gray-50 bg-[#6C8398] border border-[#6C8398] rounded-l-lg hover:bg-[#3E4952]"
                                                onclick="updateQuantity('{{ $item['id_book'] }}', -1)">
                                                -
                                            </button>

                                            {{-- Button untuk manambah quantity --}}
                                            <div class="px-4 py-2 text-sm font-medium text-black bg-white border-t">
                                                {{ $item['quantity'] }}
                                            </div>
                                            <button type="button"
                                                class="px-4 py-2 text-sm font-medium text-gray-50 bg-[#6C8398] border border-[#6C8398] rounded-r-lg hover:bg-[#3E4952]"
                                                onclick="updateQuantity('{{ $item['id_book'] }}', 1)">
                                                +
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">

                                        {{-- Button untuk menghapus item yang ada di keranjang --}}
                                        <button type="button" class="text-red-600 hover:text-red-800"
                                            onclick="removeItem('{{ $item['id_book'] }}')">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Kembali ke halaman user --}}
                <div class="px-6 py-4 dark:bg-gray-700 bg-white flex justify-center items-center">
                    <a class="px-3 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md"
                        href="{{ route('books.show') }}">Go Back</a>
                </div>

                {{-- Tempat untuk menampilkan total harga dan memasukkan nama --}}
                <div class="px-6 py-4 bg-[#F9F2EB] dark:bg-gray-700 flex justify-between items-end">
                    <div class="flex flex-col">
                        <form action="{{ route('customers.store') }}" method="POST">
                            @csrf
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter your
                                name</label>
                            <div class="flex">
                                <input type="text" name="name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Your name ..." required />
                                <button type="submit"
                                    class="mt-2 ml-2 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">enter</button>
                            </div>
                        </form>


                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-10">
                            Total Belanja: Rp <span
                                id="total-amount">{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    @if ($disabled)

                    {{-- Button untuk melakukan proses checkout --}}
                        <button onclick="checkout()"
                            class="checkout-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-300 ease-in-out">
                            Checkout
                        </button>
                    @else
                        <div class="text-red-500">Adjust your quantity</div>
                    @endif
                </div>
            @else
                {{-- Kembali ke halaman user --}}
                <div class="container mx-auto px-4 py-8 flex justify-center items-center h-64 flex-col">
                    <p class="text-xl text-gray-500 dark:text-gray-400 mb-4">No items in cart.</p>
                    <a class="px-3 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md"
                        href="{{ route('books.show') }}">Go Back</a>
                </div>
            @endif
        </div>

        <div id="dev" class="mt-4"></div>
    </div>

    {{-- fungsi untuk menyimpan rute --}}
    <script>
        @if (session('customerId'))
            window.customerId = {{ session('customerId') }};
        @endif
        const routes = {
            checkoutProcess: @json(route('checkout.process')),
            reduceStock: @json(route('reduce.stock')),
            transactionStore: @json(route('transaction.store'))
        };
    </script>

    {{-- Fungsi untuk memunculkan data --}}
    <script src="{{ asset('js/showCheckoutData.js') }}"></script>

    {{-- Fungsi untuk menjalankan fungsi mengurangi quantity dan menghapus barang --}}
    <script src="{{ asset('js/checkoutAction.js') }}"></script>

    {{-- Fungsi untuk memunculkan notifikasi --}}
    <script src="{{ asset('js/notification.js') }}"></script>

    {{-- Fungsi untuk formatting number --}}
    <script src="{{ asset('js/numberFormat.js') }}"></script>

</body>

</html>
