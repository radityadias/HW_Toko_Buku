function successNotification(message) {
    let notification = document.getElementById("toast-success");
    let toastDescription = document.getElementById("message-notification");
    toastDescription.innerHTML = message;
    notification.classList.toggle("opacity-0");
    setTimeout(() => {
        notification.classList.toggle("opacity-100");
    }, 5000);
}
