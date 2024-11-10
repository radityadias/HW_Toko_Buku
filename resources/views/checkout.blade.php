<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<div id="toast-success" class=" opacity-0 duration-300 z-50 absolute  transition-opacity right-2 top-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Check icon</span>
    </div>
    <div id="message-notification"class="ms-3 text-sm font-normal">Item moved successfully.</div>
    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>
 <div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if(isset($cart) && count($cart) > 0)
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                @php        // Find the corresponding book based on the book_id
                    $book = $books->where('book_id', $item['id_book'])->first();
                @endphp
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item['id_book'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item['title'] }}
                    </td>
                    <td class="px-6 py-4">
                        @if($book && $item['quantity'] > $book->stock)
                        @php
                            $disabled = false;
                        @endphp
                            <div class="text-red-500 text-sm">
                            {{ $item['title'] }} stock is insufficient. <br> available: {{ $book->stock }}.
                            </div>
                        @endif
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100" onclick="updateQuantity('{{ $item['id_book'] }}', -1)">
                                -
                            </button>
                            <div class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200">
                                {{ $item['quantity'] }}
                            </div>
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100" onclick="updateQuantity('{{ $item['id_book'] }}', 1)">
                                +
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-red-600 hover:text-red-800" onclick="removeItem('{{ $item['id_book'] }}')">Hapus</button>
                    </td>
                </tr>
            @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 dark:bg-gray-700 flex justify-center items-center">
                <a class="px-3 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md" href="{{ route('books.show') }}">Go Back</a>
            </div>
            <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-between items-end">
                <div class="flex flex-col">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter your name</label>
                        <div class="flex">
                            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your name ..." required />
                            <button type="submit" class="mt-2 ml-2 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Enter</button>
                        </div>
                        </form>


                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-10">
                        Total Belanja: Rp <span id="total-amount">{{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </p>
                </div>

                @if ($disabled)
                    <a href="#" onclick="checkout()" class="checkout-button text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-300 ease-in-out">
                        Checkout
                    </a>
                @else
            <div class="text-red-500">Adjust your quantity</div>
           @endif
            </div>
        @else
            <div class="container mx-auto px-4 py-8 flex justify-center items-center h-64 flex-col">
                <p class="text-xl text-gray-500 dark:text-gray-400 mb-4">No items in cart.</p>
                <a class="px-3 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md" href="{{ route('books.show') }}">Go Back</a>
            </div>
        @endif
    </div>

    <div id="dev" class="mt-4"></div>
</div>

   <script>
    const routes = {
        checkoutProcess: @json(route('checkout.process')),
        reduceStock: @json(route('reduce.stock')),
        transactionStore: @json(route('transaction.store'))
    };
    </script>
    <script src="{{asset('js/showCheckoutData.js')  }}"></script>
    <script src="{{asset('js/checkoutAction.js')  }}"></script>
    <script src="{{asset('js/notification.js')  }}"></script>
    <script src="{{ asset('js/numberFormat.js') }}"></script>

</body>
</html>
