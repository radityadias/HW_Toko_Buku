<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
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
            <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-between items-center">
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    Total Belanja: Rp <span id="total-amount">{{ number_format($totalAmount, 0, ',', '.') }}</span>
                </p>
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
    // Ambil data cart dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);

    function updateQuantity(id_book, change) {
        const index = cart.findIndex(item => item.id_book === id_book);
        if (index !== -1) {
            if (change === -1 && cart[index].quantity > 1) {
                cart[index].quantity -= 1; // Kurangi kuantitas
            } else if (change === 1) {
                cart[index].quantity += 1; // Tambah kuantitas
            }
            localStorage.setItem('cart', JSON.stringify(cart)); // Simpan kembali ke localStorage
            showCartData(); // Tampilkan data keranjang yang diperbarui
        }
    }

    function removeItem(id_book) {
        cart = cart.filter(item => item.id_book !== id_book); // Hapus item dari keranjang
        localStorage.setItem('cart', JSON.stringify(cart)); // Simpan kembali ke localStorage
        showCartData(); // Tampilkan data keranjang yang diperbarui
    }

    function showCartData() {
        // document.getElementById('total-amount').innerText = number_format(totalAmount, 0, ',', '.');

        fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart, totalAmount: totalAmount })
        })
        .then(response => response.text()) // Mengambil respon sebagai teks HTML
        .then(html => {
            // Masukkan HTML yang diterima ke dalam body untuk menggantikan konten halaman
            document.body.innerHTML = html;
        })
        .catch((error) => {
            console.error('Error:', error); // Tangani error
        });
    }

    function checkout() {
        // Kirim data ke server
        // requestToStore();
        fetch('{{ route('reduce.stock') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data.message);
            alert(data.message);
            // Hapus cart setelah checkout berhasil
            localStorage.removeItem('cart');
            // Tampilkan data keranjang yang diperbarui
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });

    }
    function requestToStore(){
    fetch('{{ route('transaction.store') }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        customer_id: 1, // Ganti dengan ID pelanggan sebenarnya, misalnya dari sesi pengguna
        total_price: totalAmount, // Pastikan totalAmount adalah jumlah total yang benar
        book_ids: cart.map(item => ({
            id_book: item.id_book, // ID buku
            quantity: item.quantity // Jumlah buku yang ingin dibeli
        })) // Array of objects with id_book and quantity
    })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        console.log(data.message);
        localStorage.removeItem('cart'); // Hapus cart setelah checkout berhasil
        window.location.reload(); // Muat ulang halaman untuk memperbarui tampilan
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Checkout gagal. Silakan coba lagi.');
    });

    }



    showCartData(); // Tampilkan data keranjang saat halaman dimuat
    </script>
    <script src="{{ asset('js/numberFormat.js') }}"></script>
</body>
</html>
