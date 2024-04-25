// Función para mostrar el submenu de configuración de maestras
function mostrarSubmenu() {
    var menu = document.getElementById("button-container");
    var submenu = document.getElementById("submenu-container");
    var secondContainer = document.getElementById("second-container");

    if (submenu.style.display === "none") {
        submenu.style.display = "block";
        secondContainer.style.display = "none";
        menu.style.display = "none"; // Oculta el menú principal
    } else {
        submenu.style.display = "none";
        menu.style.display = "flex"; // Muestra el menú principal
    }
}

// Función para mostrar el submenu de artículos
function mostrarSubMenu2() {
    var submenu = document.getElementById("second-container");

    if (submenu.style.display === "none") {
        submenu.style.display = "block";
        menu.style.display = "none"; // Oculta el menú principal
    } else {
        submenu.style.display = "none";
        menu.style.display = "flex"; // Muestra el menú principal
    }
}

// Función para mostrar el submenu de empleados
function mostrarSubmenu3() {
    var submenu = document.getElementById("third-container");

    if (submenu.style.display === "none") {
        submenu.style.display = "block";
        menu.style.display = "none"; // Oculta el menú principal
    } else {
        submenu.style.display = "none";
        menu.style.display = "flex"; // Muestra el menú principal
    }
}

// Función para mostrar el submenu de procedimientos
function mostrarSubmenu4() {
    var submenu = document.getElementById("fourth-container");

    if (submenu.style.display === "none") {
        submenu.style.display = "block";
        menu.style.display = "none"; // Oculta el menú principal
    } else {
        submenu.style.display = "none";
        menu.style.display = "flex"; // Muestra el menú principal
    }
}

// Función para mostrar el submenu de seguridad
function mostrarSubmenu5() {
    var submenu = document.getElementById("fifth-container");

    if (submenu.style.display === "none") {
        submenu.style.display = "block";
        menu.style.display = "none"; // Oculta el menú principal
    } else {
        submenu.style.display = "none";
        menu.style.display = "flex"; // Muestra el menú principal
    }
}

// Evento para el botón de cerrar sesión
document.getElementById("cerrar-sesion-btn").addEventListener("click", function(event) {
    // Evitar que el formulario se envíe automáticamente
    event.preventDefault();
    
    // Si el usuario confirma el cierre de sesión
    if (confirmacion) {
        // Enviar el formulario para cerrar sesión
        document.querySelector("form").submit();
        
        // Mostrar mensaje de cierre de sesión exitoso
        alert("HAS CERRADO SESIÓN CORRECTAMENTE");
    }
});
