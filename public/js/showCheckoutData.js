const successMessage = "checkout successful";
const enterNameMessage = "enter name your name";
let totalAmount;
console.log(window.customerId);
let cart = JSON.parse(localStorage.getItem("cart")) || [];

/* Funtion menampilkan data yang ada di keranjang */
function showCartData() {
    totalAmount = cart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
    );

    /* Melakukan fetch data ke CheckoutController */
    fetch(routes.checkoutProcess, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ cart: cart, totalAmount: totalAmount }),
    })
        .then((response) => response.text()) // Mengambil respon sebagai teks HTML
        .then((html) => {
            // Masukkan HTML yang diterima ke dalam body untuk menggantikan konten halaman
            document.body.innerHTML = html;
            console.log("pasti bisa");
            console.log(totalAmount);
        })
        .catch((error) => {
            console.error("Error:", error); // Tangani error
        });
}

/* function saat melakukan proses checkout */
function checkout() {

    // Fetch data ke CheckoutController
    fetch(routes.reduceStock, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ cart: cart, customerId: window.customerId }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log(data.message);
            requestToStore(totalAmount);
            console.log(window.customerId);
            if (window.customerId) {
                console.log("Customer ID:", window.customerId);
            }
        })
        .catch((error) => {
            // console.error("Error:", error);
            successNotification(enterNameMessage, 1000);
        });
    
}

/* Fungsi untuk simpan data ke database */
function requestToStore() {

    /* Fetch data ke TransactionController */
    fetch(routes.transactionStore, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            customer_id: window.customerId, // Ganti dengan ID pelanggan sebenarnya, misalnya dari sesi pengguna
            total_price: totalAmount, // Pastikan totalAmount adalah jumlah total yang benar
            books: cart, // Array of objects with id_book and quantity
        }),
    })
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((data) => {
            console.log(data.message);
            localStorage.removeItem("cart"); // Hapus cart setelah checkout berhasil
            successNotification(successMessage, 1000);
            setTimeout(() => {
                window.location.reload();
            }, 500);
        })
        .catch((error) => {
            // console.error("Error:", error);
            // successNotification(enterNameMessage, 2000);
        });
}

showCartData(); // Tampilkan data keranjang saat halaman dimuat
