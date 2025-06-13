let hasUnsavedChanges = false;

function handleUnsavedChanges(event) {
    if (hasUnsavedChanges && (event.key === 'F5' || (event.ctrlKey && event.key === 'r'))) {
        showCustomAlert(event, 'reload', 'Tienes cambios sin guardar. Si continuas, perderás estos cambios.', handleAction);
    }
}

function handleUnsavedLinks(event) {
    if (hasUnsavedChanges && event.target.tagName === 'A') {
        showCustomAlert(event, event.target.href, 'Tienes cambios sin guardar. Si sales, perderás estos cambios.', (href) => window.location.href = href);
    }
}

function showCustomAlert(event, action, message, callback) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            actions: 'd-flex justify-content-between w-100',
            confirmButton: 'btn btn-primary me-4',
            cancelButton: 'btn btn-danger ms-4'
        },
        buttonsStyling: false,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            hasUnsavedChanges = false;
            if (callback) callback(action);
        }
    });
}

function handleAction(action) {
    if (action === 'reload') location.reload();
    else if (action === 'close') window.close();
}

document.addEventListener('keydown', handleUnsavedChanges);
document.addEventListener('click', handleUnsavedLinks);
document.addEventListener('input', () => hasUnsavedChanges = true);
