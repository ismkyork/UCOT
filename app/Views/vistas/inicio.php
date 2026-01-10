<?=$header?>



    <h1>página de inicio</h1>

<nav class="navbar navbar-expand-lg navbar-light bg-light ">
    <a class="navbar-brand">Alumno</a>
   
    <div id="my-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
           
            <li class="nav-item active">
                <a class="nav-link" href="alumno/calendario">calendario <span class="sr-only"></span></a>
            </li>


             <li class="nav-item active">
                <a class="nav-link" href="alumno/factura">factura <span class="sr-only"></span></a>
            </li>
             <li class="nav-item active">
                <a class="nav-link" href="alumno/mis_citas">mis citas <span class="sr-only"></span></a>
            </li>
        </ul>
    </div>
</nav>
<br>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
    <a class="navbar-brand">Autenticación</a>
   
    <div id="my-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="auth/login">inicia sesión <span class="sr-only"></span></a>
            </li>

             <li class="nav-item active">
                <a class="nav-link" href="auth/registro">Registro <span class="sr-only"></span></a>
            </li>
            
        </ul>
    </div>
</nav>
<br>
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
    <a class="navbar-brand">Profesor</a>
   
    <div id="my-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="profesor/config_horarios">configuración de horarios <span class="sr-only"></span></a>
            </li>

             <li class="nav-item active">
                <a class="nav-link" href="profesor/dashboard">dashboard <span class="sr-only"></span></a>
            </li>
            
             <li class="nav-item active">
                <a class="nav-link" href="profesor/pagos">citas <span class="sr-only"></span></a>
            </li>
        
        </ul>
    </div>
</nav>




<?=$footer?>