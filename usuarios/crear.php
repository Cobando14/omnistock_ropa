<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Crear Usuario";
include("../includes/header.php");

if($_POST){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $documento = $conn->real_escape_string($_POST['documento']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
    $tipo_documento = $conn->real_escape_string($_POST['tipo_documento'] ?? 1);
    $rol = $conn->real_escape_string($_POST['rol'] ?? 1);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nom_usuario, apell_usuario, N_documento, correo_usuario, password_usuario, rol, tipo_documento, telefono)
    VALUES ('$nombre', '$apellido', '$documento', '$correo', '$password', $rol, $tipo_documento, '$telefono')";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit;
    } else {
        $error = "Error al crear el usuario: " . $conn->error;
    }
}

// Obtener roles para el select
$roles_result = $conn->query("SELECT id_rol, nombre_rol FROM rol");
$tipos_resultado = $conn->query("SELECT id_tip_documento, tip_documento FROM tip_documento");
?>

<div class="page-header">
    <h2>Crear Nuevo Usuario</h2>
    <p style="color: #6b7280;">Completa el formulario para agregar un nuevo usuario</p>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" required>
    </div>

    <div class="form-group">
        <label for="tipo_documento">Tipo de Documento *</label>
        <select id="tipo_documento" name="tipo_documento" required>
            <option value="">Seleccionar tipo...</option>
            <?php while($row = $tipos_resultado->fetch_assoc()): ?>
                <option value="<?php echo $row['id_tip_documento']; ?>"><?php echo htmlspecialchars($row['tip_documento']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="documento">Documento *</label>
        <input type="text" id="documento" name="documento" required>
    </div>

    <div class="form-group">
        <label for="correo">Correo Electrónico *</label>
        <input type="email" id="correo" name="correo" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono">
    </div>

    <div class="form-group">
        <label for="password">Contraseña *</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label for="rol">Rol *</label>
        <select id="rol" name="rol" required>
            <option value="">Seleccionar rol...</option>
            <?php while($row = $roles_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id_rol']; ?>"><?php echo htmlspecialchars($row['nombre_rol']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <button type="submit" style="width: 100%;">Guardar Usuario</button>
</form>

<?php include("../includes/footer.php"); ?>