let booksuccessadd="Buku ditambahkan ke cart"

function successNotification(message, duration) {
    let notification = document.getElementById("toast-success");
    let toastDescription = document.getElementById("message-notification");
    toastDescription.innerHTML = message;
    console.log("disini");
    notification.classList.remove("opacity-0");
    notification.classList.add("opacity-100");
    setTimeout(() => {
        notification.classList.remove("opacity-100");
        notification.classList.add("opacity-0");
    }, duration);
}
