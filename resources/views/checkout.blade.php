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
                   <a href="#" id="checkout-button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    checkout</a>
                    <div id="dev"></div>
    @else
        <p>No items in cart.</p>
    @endif

    <script>
        // Ambil data cart dari localStorage

        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        let totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        console.log(totalAmount);
        // Kirim data cart ke Laravel menggunakan Fetch API
        document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const checkoutButton = document.getElementById('checkout-button');
        if (checkoutButton) {
            checkoutButton.addEventListener('click', function() {
                // Pastikan variabel `cart` sudah didefinisikan
                const cart = []; // Contoh: ganti dengan data cart yang sesuai

                fetch('{{ route("decrease.stocks") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    body: JSON.stringify({ cart: cart })
                })
                .then(response => response.json())
                .then(data => {
                    const responseMessage = document.getElementById('responseMessage');
                    if (responseMessage) {
                        responseMessage.innerText = data.message; // Tampilkan pesan dari response
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const responseMessage = document.getElementById('responseMessage');
                    if (responseMessage) {
                        responseMessage.innerText = 'Gagal mengurangi stok.';
                    }
                });
            });
        } else {
            console.error('Button "checkout-button" tidak ditemukan');
        }
    }, 800);
});

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
//     checkoutButton.addEventListener('click', function() {
//     fetch('{{ route("decrease.stocks") }}', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
//       },
//       body: JSON.stringify({ cart: cart })
//     })
//     .then(response => response.json())
//     .then(data => {
//       document.getElementById('responseMessage').innerText = data.message; // Tampilkan pesan
//     })
//     .catch(error => {
//       console.error('Error:', error);
//       document.getElementById('responseMessage').innerText = 'Gagal mengurangi stok.';
//     });
//   });

    </script>
</body>
</html>
