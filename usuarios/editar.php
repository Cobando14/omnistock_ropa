<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$page_title = "Editar Usuario";
include("../includes/header.php");

$sql = "SELECT * FROM usuarios WHERE id_usuario=$id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo '<div class="alert alert-danger">Usuario no encontrado</div>';
    include("../includes/footer.php");
    exit;
}

$usuario = $result->fetch_assoc();

$sql_tip = "SELECT * FROM tip_documento";
$result_tip = $conn->query($sql_tip);

$sql_roles = "SELECT * FROM rol";
$result_roles = $conn->query($sql_roles);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $tipo_documento = $conn->real_escape_string($_POST['tipo_documento']);
    $n_documento = $conn->real_escape_string($_POST['n_documento']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $rol = $conn->real_escape_string($_POST['rol']);
    $password = $_POST['password'];

    if(!empty($password)){
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password = $usuario['password_usuario'];
    }

    $sql = "UPDATE usuarios SET
    nom_usuario='$nombre',
    apell_usuario='$apellido',
    tipo_documento=$tipo_documento,
    N_documento='$n_documento',
    telefono='$telefono',
    correo_usuario='$correo',
    rol=$rol,
    password_usuario='$password'
    WHERE id_usuario=$id";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<div class="page-header">
    <h2>Editar Usuario</h2>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nom_usuario'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="apellido">Apellido *</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apell_usuario'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="tipo_documento">Tipo de Documento *</label>
        <select id="tipo_documento" name="tipo_documento" required>
            <?php while($row = $result_tip->fetch_assoc()){ ?>
                <option value="<?php echo $row['id_tip_documento']; ?>" <?php if(($usuario['tipo_documento'] ?? null) == $row['id_tip_documento']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['tip_documento']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="n_documento">Número de Documento *</label>
        <input type="text" id="n_documento" name="n_documento" value="<?php echo htmlspecialchars($usuario['N_documento'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label for="correo">Correo Electrónico *</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo_usuario'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="rol">Rol *</label>
        <select id="rol" name="rol" required>
            <?php while($row = $result_roles->fetch_assoc()){ ?>
                <option value="<?php echo $row['id_rol']; ?>" <?php if(($usuario['rol'] ?? null) == $row['id_rol']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['nombre_rol']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="password">Nueva Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Dejar vacío si no desea cambiarla">
    </div>

    <div style="display: flex; gap: 1rem;">
        <button type="submit" style="flex: 1;">Actualizar Usuario</button>
        <a href="index.php" class="btn" style="flex: 1; text-align: center; text-decoration: none; background-color: #6b7280;">Cancelar</a>
    </div>
</form>

<?php include("../includes/footer.php"); ?>

</div>

</div>

</body>

</html>