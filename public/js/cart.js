// Fungsi untuk menambahkan buku ke keranjang
function addToCart(book) {
    // Ambil cart dari Local Storage, atau buat array kosong jika belum ada
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Periksa apakah buku sudah ada di cart
    const existingBook = cart.find((item) => item.id_book === book.id_book);

    if (existingBook) {
        // Jika buku sudah ada, tambah quantity-nya
        existingBook.quantity += 1;
    } else {
        // Jika buku belum ada, tambahkan dengan quantity default = 1
        book.quantity = 1;
        cart.push(book);
    }

    // Simpan kembali ke Local Storage
    localStorage.setItem("cart", JSON.stringify(cart));

    console.log("Book added to cart:", book);
}
// Tambahkan event listener ke semua tombol Add to Cart
document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", function (e) {
        e.preventDefault();
        // Ambil data buku dari atribut data tombol
        const book = {
            id_book: this.getAttribute("data-id_book"),
            title: this.getAttribute("data-title"),
            price: parseFloat(this.getAttribute("data-price")),
        };

        // Panggil fungsi addToCart untuk menyimpan data
        addToCart(book);
    });
});
