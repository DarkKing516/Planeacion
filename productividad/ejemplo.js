// Este es solo un ejemplo de cómo podrías agregar interactividad al formulario, como validar campos o realizar acciones al enviar el formulario.
// Puedes agregar más funcionalidad según tus necesidades.

// Ejemplo de validación básica del formulario
document.querySelector('form').addEventListener('submit', function(event) {
    // Validar que el campo de nombre no esté vacío
    var nombre = document.getElementById('nombre').value;
    if (nombre.trim() === '') {
        alert('Por favor, ingresa tu nombre.');
        event.preventDefault(); // Evitar el envío del formulario si no se pasa la validación
    }
});

document.getElementById('add-more').addEventListener('click', function() {
    // Clonar el primer conjunto de campos adicionales y agregarlos al formulario
    var additionalField = document.querySelector('.additional-field');
    var clone = additionalField.cloneNode(true);
    clone.style.display = 'block'; // Mostrar los campos clonados
    
    // Limpiar los valores de los campos clonados
    clone.querySelector('[name="telefono[]"]').value = '';
    clone.querySelector('[name="estudio[]"]').value = '';
    clone.querySelector('[name="actividad[]"]').value = '';
    
    additionalField.parentNode.appendChild(clone);
});
