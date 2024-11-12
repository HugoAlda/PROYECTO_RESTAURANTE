// Función para confirmar la asignación de una mesa
function confirmarAsignacion(formAsignar) {
    Swal.fire({
        title: '¿Seguro que quieres asignar esta mesa?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, asignar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            formAsignar.submit(); // Enviar el formulario si se confirma
        }
    });
}

// Función para confirmar la desasignación de una mesa
function confirmarDesasignacion(formDesasignar) {
    Swal.fire({
        title: '¿Seguro que quieres desasignar esta mesa?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desasignar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            formDesasignar.submit(); // Enviar el formulario si se confirma
        }
    });
}
