document.addEventListener('DOMContentLoaded', function() {
    const inputPrecio = document.getElementById('precio_clase');
    const displayBs = document.getElementById('calc_bs');
    
    // CORRECCIÓN AQUÍ: Leemos la variable global que definimos en la vista
    const tasa = window.tasaBCV || 0;

    if (inputPrecio && displayBs) {
        function calcular() {
            let dolares = parseFloat(inputPrecio.value) || 0;
            let bolivares = dolares * tasa;
            
            displayBs.textContent = bolivares.toLocaleString('es-VE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        inputPrecio.addEventListener('input', calcular);
        // Ejecutamos una vez al cargar para que se vea el monto inicial
        calcular();
    }
});