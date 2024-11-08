{{-- resources/views/checkout.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Checkout Page</h1>
@if(isset($cart) && count($cart) > 0)
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
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
                    @foreach ($cart as $index => $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item['id_book'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item['title'] }}
                        </td>
                        <td class="px-6 py-4">
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

        <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-between items-center">
            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                Total Belanja: Rp <span id="total-amount">{{ number_format($totalAmount, 0, ',', '.') }}</span>
            </p>

            <a href="#" onclick="checkout()" class="checkout-button text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-300 ease-in-out">
                Checkout
            </a>
        </div>
    </div>

    <div id="dev" class="mt-4"></div>
</div>
@else
<div class="container mx-auto px-4 py-8 flex justify-center items-center h-64">
    <p class="text-xl text-gray-500 dark:text-gray-400">No items in cart.</p>
</div>
@endif

<script>
    // Ambil data cart dari localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

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
        let totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
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
            // Hapus cart setelah checkout berhasil
            localStorage.removeItem('cart');
            showCartData(); // Tampilkan data keranjang yang diperbarui
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Checkout gagal. Silakan coba lagi.'); // Ganti dengan fungsi notifikasi jika ada
        });
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Format number to string
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            return (prec ? toFixedFix(n, prec) : '' + Math.round(n)).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + sep) + (prec ? dec + ('' + Math.abs(n - Math.round(n))).slice(2, prec + 2) : '');
            };
        return toFixedFix(n, prec);
    }

    showCartData(); // Tampilkan data keranjang saat halaman dimuat
    </script>
</body>
</html>
