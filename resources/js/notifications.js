document.addEventListener('DOMContentLoaded', function () {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    if (typeof successMessage !== 'undefined' && successMessage) {
        Toast.fire({
            icon: 'success',
            title: successMessage
        });
    } else if (typeof errorMessage !== 'undefined' && errorMessage) {
        Toast.fire({
            icon: 'error',
            title: errorMessage
        });
    }
});
