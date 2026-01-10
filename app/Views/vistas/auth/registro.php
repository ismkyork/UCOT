<?=$header?> 
<h1>Registro</h1>

<form action="<?=base_url('auth/registrarUsuario')?>" method="POST">

    <div class="form-group mb-3">
        <label for="name">Nombre</label>
        <input id="name" value="<?=old('name')?>" class="form-control" type="text" name="name" placeholder="Ej: Enyerson">
    </div>

    <div class="form-group mb-3">
        <label for="apellido">Apellido</label>
        <input id="apellido" value="<?=old('apellido')?>" class="form-control" type="text" name="apellido" placeholder="Ej: Arevalo">
    </div>

    <div class="form-group mb-3">
        <label for="email">Email</label>
        <input id="email" value="<?=old('email')?>" class="form-control" type="email" name="email" placeholder="Ej: panconqueso@gmail.com">
    </div>

    <div class="form-group mb-3">
        <label for="password">Contraseña</label>
        <input id="password" class="form-control" type="password" name="password" placeholder="Ej:*********">
    </div> <div class="form-group mb-3">
        <label for="tipo_user">Tipo de Usuario</label>
        <select id="tipo_user" class="form-control" name="tipo_user">
            <option value="" disabled selected>Seleccione un tipo de usuario</option>
            <option value="Profesor">Profesor</option>
            <option value="Estudiante">Estudiante</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success btn-lg w-100">
        <i class="fas fa-save me-2"></i> Registrar
    </button>

</form> <?=$footer?>