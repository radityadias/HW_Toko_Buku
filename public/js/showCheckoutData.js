const successMessage = "checkout successful";
// const enterName = "enter name";
// let isNameNull = "true";
let totalAmount;
let cart = JSON.parse(localStorage.getItem("cart")) || [];
function getRandomNumberInRange() {
    return Math.floor(Math.random() * 4) + 1;
}
function showCartData() {
    totalAmount = cart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
    );

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
// function handleName(e) {

//     isNameNull = false;
//     console.log(isNameNull);
// }
function checkout() {
    // Kirim data ke server
    // console.log(isNameNull);
    // if (!isNameNull) {
    fetch(routes.reduceStock, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ cart: cart }),
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
            console.error("Error:", error);
        });
    // } else {
    //     successNotification(enterName, 2000);
    // }
}
function requestToStore() {
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
            successNotification(successMessage, 3000);
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

showCartData(); // Tampilkan data keranjang saat halaman dimuat
