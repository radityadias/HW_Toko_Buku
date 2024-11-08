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

    {{-- Jika data cart ada, tampilkan --}}
    @if(isset($cart) && count($cart) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Title</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item['id_book'] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['price'] }}</td>
                    </tr>
                @endforeach

        </table>
                    <p>total belanja:{{ $totalAmount }}</p>
                   <a href="#" id="checkout-button" class="checkout-button text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    checkout</a>
                    <div id="dev"></div>
    @else
        <p>No items in cart.</p>
    @endif

    <script>
        // Ambil data cart dari localStorage

        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        let totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart, totalAmount: totalAmount})
        })
        .then(response => response.text()) // Mengambil respon sebagai teks HTML
        .then(html => {
            // Masukkan HTML yang diterima ke dalam body untuk menggantikan konten halaman
            document.body.innerHTML = html;
        })
        .catch((error) => {
            console.error('Error:', error); // Tangani error
        });

        // Modifikasi fungsi checkout
    function checkout() {
        // Ambil cart dari localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || {};

        // Konversi objek cart ke array yang sesuai dengan kebutuhan backend
        let cartArray = Object.values(cart).map(item => ({
            book_id: item.id_book,
            quantity: item.quantity,
            price: item.price
        }));

        // Tambahkan CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Kirim data ke server
        fetch('{{ route('reduce.stock') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ cart: cartArray })
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
            updateCartDisplay();
            // Tampilkan pesan sukses
            showNotification('Checkout berhasil!');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Checkout gagal. Silakan coba lagi.');
        });
    }

    // Tambahkan event listener atau panggil pada tombol checkout
    setTimeout(() => {
    document.querySelector('.checkout-button').addEventListener('click', checkout);
    }, 800);


    </script>
</body>
</html>
