// Esperamos a que el DOM esté cargado
document.addEventListener("DOMContentLoaded", function () {
    
    // Llamamos al formulario mediante su ID
    const form = document.getElementById("login");

    // Agregamos un evento de escucha para que se ejecute cuando se envia el formulario
    form.addEventListener("submit", function (event) {

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("pwd").value.trim();

        // VALIDACIÓN USERNAME
        // Campo vacío
        if (username === "" || username === null) {
            alert('El nombre de usuario no puede estar vacío');
            event.preventDefault();
            exit();
        }

        // Sin números
        const nums = /[0-9]/;
        if (nums.test(username)) {
            alert('El nombre de usuario no puede contener números');
            event.preventDefault();
            exit();
        }

        // VALIDACIÓN CONTRASEÑA
        // Campo vacío
        if (password === "" || password === null) {
            alert('La contraseña no puede estar vacía');
            event.preventDefault();
            exit();
        }

        // Más de 8 caracteres
        if (password < 8) {
            alert('La contraseña debe tener más de 8 caracteres');
            event.preventDefault();
            exit();
        }

        // Contener 1 letra
        const letra = /[a-zA-Z]/;
        if (!letra.test(password)) {
            alert("La contraseña debe contener al menos una letra");
            event.preventDefault();
            exit();
        }

        // Contener 1 numero
        const num = /[0-9]/;
        if (!num.test(password)) {
            alert("La contraseña debe contener al menos un número");
            event.preventDefault();
            exit();
        }

        // Contener 1 mayúscula
        const mayus = /[A-Z]/;
        if (!mayus.test(password)) {
            alert("La contraseña debe contener al menos una letra mayúscula");
            event.preventDefault();
            exit();
        }

        // Contener 1 minúscula
        const minus = /[a-z]/;
        if (!minus.test(password)) {
            alert("La contraseña debe contener al menos una letra minúscula");
            event.preventDefault();
            exit();
        }

    });
});
