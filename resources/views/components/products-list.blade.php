<div class="grid grid-rows-4 grid-flow-col gap-4">
@foreach ( $products as $product )
<div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <a href="#">
        <img class="p-8 rounded-t-lg" src="https://placehold.co/600x400" alt="product image" />
    </a>
    <div class="px-5 pb-5">
        <a href="#">
            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $product->title }}</h5>
        </a>

        <div class="flex items-center justify-between">
            <span class="text-3xl font-bold text-gray-900 dark:text-white">Rp{{ $product->price }}</span>
            <a href="#"   data-id_book="{{ $product->book_id }}" data-title="{{ $product->title }}" data-price="{{ $product->price }}" class="add-to-cart text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add to cart</a>
        </div>
    </div>
</div>


@endforeach
</div>
    <script>
      // Fungsi untuk menambahkan buku ke keranjang
    function addToCart(book) {
        // Ambil cart dari Local Storage, atau buat array kosong jika belum ada
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Periksa apakah buku sudah ada di cart
        const existingBook = cart.find(item => item.id_book === book.id_book);

        if (existingBook) {
            // Jika buku sudah ada, tambah quantity-nya
            existingBook.quantity += 1;
        } else {
            // Jika buku belum ada, tambahkan dengan quantity default = 1
            book.quantity = 1;
            cart.push(book);
        }

        // Simpan kembali ke Local Storage
        localStorage.setItem('cart', JSON.stringify(cart));

        console.log('Book added to cart:', book);
    }
        // Tambahkan event listener ke semua tombol Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function (e) {
              e.preventDefault();
            // Ambil data buku dari atribut data tombol
            const book = {
                id_book: this.getAttribute('data-id_book'),
                title: this.getAttribute('data-title'),
                price: parseFloat(this.getAttribute('data-price'))
            };

            // Panggil fungsi addToCart untuk menyimpan data
            addToCart(book);
        });
    });
    // // Ambil data produk dari PHP ke JavaScript
    // const products = @json($products);

    // // Loop dan cetak nama produk ke console
    // products.forEach(product => {
    //     console.log(product.title);
    // });
</script>
