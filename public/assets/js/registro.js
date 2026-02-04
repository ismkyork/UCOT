function actualizarSeguridad(input) {
    // Si pasaste 'this' desde el HTML, 'input' es el elemento
    const pass = input.value; 
    const bar = document.getElementById('password-strength-bar');
    const feedback = document.getElementById('password-feedback');

    let strength = 0;
    if (pass.length >= 8) strength += 25;
    if (/[A-Z]/.test(pass)) strength += 25;
    if (/[0-9]/.test(pass)) strength += 25;
    if (/[^A-Za-z0-9]/.test(pass)) strength += 25;

    bar.style.width = strength + "%";
    bar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');

    // Cambiamos texto Y color de la letra simultáneamente
    if (strength === 0) {
        bar.classList.add('bg-danger');
        feedback.innerText = "Seguridad: Muy débil";
        feedback.style.setProperty('color', '#dc3545', 'important'); // Forzamos el color
    } else if (strength <= 25) {
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
        feedback.style.setProperty('color', '#198754', 'important'); // Verde final
    }
}