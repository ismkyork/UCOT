// Función para validar campos que solo aceptan texto (Nombres, Apellidos, etc.)
function validarSoloLetras(input) {
    let regex = /[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g;
    input.value = input.value.replace(regex, "");
}

function togglePasswordById(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (input) {
        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        }
    } else {
        console.error("No se encontró el input con ID:", inputId);
    }
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



function validarCoincidencia() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const matchIcon = document.getElementById('match-icon');
    const matchFeedback = document.getElementById('match-feedback');
    const btnSubmit = document.querySelector('button[type="submit"]'); // Asegúrate que seleccione tu botón

    if (!password || !confirmPassword || !matchIcon || !matchFeedback) return;

    const val1 = password.value;
    const val2 = confirmPassword.value;

    // Si el campo de confirmación está vacío
    if (val2.length === 0) {
        matchIcon.className = "fas fa-lock text-muted";
        matchFeedback.innerText = "";
        if(btnSubmit) btnSubmit.disabled = true;
        return;
    }

    // RESTRICCIÓN: Deben coincidir Y tener entre 6 y 15 caracteres
    const longitudValida = val1.length >= 6 && val1.length <= 15;
    const coinciden = val1 === val2;

    if (coinciden && longitudValida) {
        matchIcon.className = "fas fa-check-circle text-success";
        matchFeedback.innerText = "Las contraseñas coinciden";
        matchFeedback.style.color = "#198754";
        if(btnSubmit) btnSubmit.disabled = false; 
    } else {
        matchIcon.className = "fas fa-times-circle text-danger";
        matchFeedback.style.color = "#dc3545";
        
        if (!coinciden) {
            matchFeedback.innerText = "Las contraseñas no coinciden";
        } else if (!longitudValida) {
            matchFeedback.innerText = "La contraseña debe tener entre 6 y 15 caracteres";
        }
        
        if(btnSubmit) btnSubmit.disabled = true; 
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
/*popover*/ 
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los popovers de la página
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            trigger: 'focus' // Esto hace que se cierre al hacer clic fuera
        })
    })
});