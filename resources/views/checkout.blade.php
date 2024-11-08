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
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalAmount = cart.reduce((total, item) => total + (item.price * item.quantity), 0)
        fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart, totalAmount: totalAmount})
        })
        .then(response => response.text()) // mengambil respon sebagai teks html
        .then(html => {
            // masukkan html yang diterima ke dalam body untuk menggantikan konten halaman
            document.body.innerhtml = html;
        })
        .catch((error) => {
            console.error('error:', error); // tangani error
        });
        fetch('{{ route('reduce.stock') }}',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart, totalAmount: totalAmount})
        .then(response => response.text()) // mengambil respon sebagai teks html
        .then()
        .catch((error) => {
            console.error('error:', error); // tangani error
        });

        })

    </script>
</body>
</html>
