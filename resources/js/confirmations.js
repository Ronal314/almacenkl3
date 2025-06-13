function confirmSubmit(formId, fields) {
    let fieldDetails = '';
    for (const [label, fieldId] of Object.entries(fields)) {
        let value;
        if (fieldId.startsWith('document.getElementById')) {
            value = eval(fieldId);
        } else {
            value = document.getElementById(fieldId).value;
        }
        fieldDetails += `<p class="text-start"><strong>${label}:</strong> ${value}</p>`;
    }

    Swal.fire({
        title: '¿Está seguro de guardar?',
        html: fieldDetails,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            actions: 'd-flex justify-content-between w-100',
            confirmButton: 'btn btn-success me-4',
            cancelButton: 'btn btn-danger ms-4'
        },
        buttonsStyling: false,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

function confirmToggleEstado(id, currentEstado, descripcion, baseUrl) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `¡Estás a punto de cambiar el estado de la categoría: <strong>${descripcion}</strong>!`,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            actions: 'd-flex justify-content-between w-100',
            confirmButton: 'btn btn-success me-4',
            cancelButton: 'btn btn-danger ms-4'
        },
        buttonsStyling: false,
        confirmButtonText: 'Sí, cambiar estado',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            toggleEstado(id, currentEstado, baseUrl);
        }
    });
}

window.confirmSubmit = confirmSubmit;
window.confirmToggleEstado = confirmToggleEstado;

function toggleEstado(id, currentEstado, baseUrl) {
    var newEstado = currentEstado == 1 ? 0 : 1;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(baseUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id: id, 
                estado: newEstado
            })
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            alert('Error al actualizar el estado');
        });
}
