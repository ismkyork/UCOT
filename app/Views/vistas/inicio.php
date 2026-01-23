<?= $header ?>

<?= $header ?>

<div id="cookie-joke" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); display: flex; align-items: center; justify-content: center; z-index: 10000; font-family: Arial, sans-serif;">
    <div style="background: white; padding: 40px; border-radius: 20px; max-width: 500px; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,0.5);">
        <h2 style="color: #333; margin-bottom: 20px;">🍪 ¡UCOT requiere Cookies!</h2>
        <p style="color: #666; line-height: 1.6; font-size: 16px;">
            Para garantizar la seguridad de tus tutorías y cumplir con la normativa de la <strong>Unión Universitaria de Programadores</strong>, debes aceptar nuestras cookies de seguimiento de estrés y consumo de café.
        </p>
        <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
            <button onclick="bromita()" style="background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold;">Aceptar todo</button>
            <button onclick="bromita()" style="background: #dc3545; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold;">Rechazar y salir</button>
        </div>
    </div>
</div>

<script>
function bromita() {
    alert("¡CAÍSTE! 🤣\n\nMenos cookies y más código. Atte: Maykel.");
    document.getElementById('cookie-joke').style.display = 'none';
}
</script>

<h1 class="texto-animado">Bienvenido a UCOT</h1>

 <?=$login?> 

<?=$footer?>
