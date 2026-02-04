// Función para validar campos que solo aceptan texto (Nombres, Apellidos, etc.)
function validarSoloLetras(input) {
    let regex = /[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g;
    input.value = input.value.replace(regex, "");
}

// Función genérica para mostrar/ocultar contraseña 
function togglePassword() {
    const passInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (passInput && icon) {
        if (passInput.type === "password") {
            passInput.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}

// Asegúrate de que el botón en tu HTML sea así:
// <button type="submit" id="btn-submit" class="btn btn-success" disabled>ACTUALIZAR</button>

function validarCoincidencia() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const matchIcon = document.getElementById('match-icon');
    const matchFeedback = document.getElementById('match-feedback');
    const btnSubmit = document.getElementById('btn-submit');

    if (!password || !confirmPassword || !matchIcon || !matchFeedback) return;

    const val1 = password.value;
    const val2 = confirmPassword.value;

    // Si el segundo campo está vacío, reseteamos el estado
    if (val2.length === 0) {
        matchIcon.className = "fas fa-lock text-muted";
        matchFeedback.innerText = "";
        if(btnSubmit) btnSubmit.disabled = true;
        return;
    }

    // Validación de coincidencia
    if (val1 === val2 && val1.length > 0) {
        matchIcon.className = "fas fa-check-circle text-success";
        matchFeedback.innerText = "Las contraseñas coinciden";
        matchFeedback.style.color = "#198754";
        if(btnSubmit) btnSubmit.disabled = false; // Habilita el botón si coinciden
    } else {
        matchIcon.className = "fas fa-times-circle text-danger";
        matchFeedback.innerText = "Las contraseñas no coinciden";
        matchFeedback.style.color = "#dc3545";
        if(btnSubmit) btnSubmit.disabled = true; // Deshabilita si no coinciden
    }
}

/* feedback*/ 
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('feedbackModal');
    const openBtn = document.getElementById('openFeedback');
    const closeBtn = document.getElementById('closeFeedback');
    const closeBtnAux = document.getElementById('btnCerrarModal');

    if (openBtn) {
        openBtn.onclick = () => modal.style.display = 'flex';
        
        const cerrar = () => modal.style.display = 'none';
        
        closeBtn.onclick = cerrar;
        closeBtnAux.onclick = cerrar;
        window.onclick = (e) => { if (e.target === modal) cerrar(); };
    }
});