function successToast(message) {
    Lobibox.notify('success', {
        pauseDelayOnHover: true,
        size: 'mini',
        rounded: true,
        icon: 'bi bi-check2-circle',
        delayIndicator: false,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        msg: message
    });
}

function errorToast(message) {
    Lobibox.notify('error', {
        pauseDelayOnHover: true,
        size: 'mini',
        rounded: true,
        delayIndicator: false,
        icon: 'bi bi-x-circle',
        continueDelayOnInactiveTab: false,
        position: 'top right',
        msg: message
    });
}

// Diğer bildirim türleri için benzer fonksiyonlar ekleyebilirsiniz (info, warning vb.)
