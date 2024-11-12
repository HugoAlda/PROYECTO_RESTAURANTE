document.addEventListener('DOMContentLoaded', () => {
    const btnAsignar = document.getElementById('btn-asignar');
    const formAsignar = document.getElementById('form-asignar');

    if (btnAsignar && formAsignar) {
        
        btnAsignar.addEventListener('click', (event) => {
            event.preventDefault();

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
                    formAsignar.submit();
                }
            });
        });
    }

    const btnDesasignar = document.getElementById('btn-desasignar');
    const formDesasignar = document.getElementById('form-desasignar');

    if (btnDesasignar && formDesasignar) {
        btnDesasignar.addEventListener('click', (event) => {
            event.preventDefault();

            Swal.fire({
                title: '¿Seguro que quieres desasignar esta mesa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desasignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formDesasignar.submit();
                }
            });
        });
    }
});
