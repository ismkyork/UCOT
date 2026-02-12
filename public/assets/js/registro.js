// registro.js

function actualizarSeguridad(input) {
    let pass = input.value; 
    const bar = document.getElementById('password-strength-bar');
    const feedback = document.getElementById('password-feedback');

    // --- RESTRICCIÓN MÁXIMA (15 caracteres) ---
    if (pass.length > 15) {
        input.value = pass.slice(0, 15); // Corta el excedente
        pass = input.value; // Actualiza la variable
    }

    // Reiniciar clases de la barra
    bar.className = 'progress-bar'; // Limpia clases previas
    
    // --- RESTRICCIÓN MÍNIMA (6 caracteres) ---
    // Si escribió algo pero es menor a 6, mostramos error
    if (pass.length > 0 && pass.length < 6) {
        bar.style.width = "10%";
        bar.classList.add('bg-danger');
        feedback.innerText = "Mínimo 6 caracteres";
        feedback.style.setProperty('color', '#dc3545', 'important');
        return; // Salimos de la función, no calculamos fuerza aún
    }

    // Si está vacío
    if (pass.length === 0) {
        bar.style.width = "0%";
        feedback.innerText = "Seguridad: Muy débil";
        return;
    }

    // --- CÁLCULO DE FUERZA (Solo si cumple longitud 6-15) ---
    let strength = 0;
    
    // Puntos por longitud (dentro del rango permitido)
    if (pass.length >= 6) strength += 10; // Cumple el mínimo
    if (pass.length >= 10) strength += 15; // Longitud ideal

    // Puntos por complejidad
    if (/[A-Z]/.test(pass)) strength += 25; // Mayúscula
    if (/[0-9]/.test(pass)) strength += 25; // Número
    if (/[^A-Za-z0-9]/.test(pass)) strength += 25; // Carácter especial

    bar.style.width = strength + "%";

    if (strength <= 30) {
        bar.classList.add('bg-danger');
        feedback.innerText = "Seguridad: Débil";
        feedback.style.setProperty('color', '#dc3545', 'important');
    } else if (strength <= 50) {
        bar.classList.add('bg-warning');
        feedback.innerText = "Seguridad: Media";
        feedback.style.setProperty('color', '#ffc107', 'important');
    } else if (strength <= 75) {
        bar.classList.add('bg-info');
        feedback.innerText = "Seguridad: Buena";
        feedback.style.setProperty('color', '#0dcaf0', 'important');
    } else {
        bar.classList.add('bg-success');
        feedback.innerText = "Seguridad: Excelente";
        feedback.style.setProperty('color', '#198754', 'important'); 
    }
}