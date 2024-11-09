function updateQuantity(id_book, change) {
    const index = cart.findIndex((item) => item.id_book === id_book);
    if (index !== -1) {
        if (change === -1 && cart[index].quantity > 1) {
            cart[index].quantity -= 1; // Kurangi kuantitas
        } else if (change === 1) {
            cart[index].quantity += 1; // Tambah kuantitas
        }
        localStorage.setItem("cart", JSON.stringify(cart)); // Simpan kembali ke localStorage
        showCartData();
    }
}

function removeItem(id_book) {
    cart = cart.filter((item) => item.id_book !== id_book); // Hapus item dari keranjang
    localStorage.setItem("cart", JSON.stringify(cart)); // Simpan kembali ke localStorage
    showCartData();
}
