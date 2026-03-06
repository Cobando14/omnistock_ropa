<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Crear Rol";
include("../includes/header.php");

if($_POST){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');

    $sql = "INSERT INTO rol (nombre_rol, descripcion_rol)
    VALUES ('$nombre', '$descripcion')";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit;
    } else {
        $error = "Error al crear el rol: " . $conn->error;
    }
}
?>

<div class="page-header">
    <h2>Crear Nuevo Rol</h2>
    <p style="color: #6b7280;">Completa el formulario para agregar un nuevo rol</p>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre del Rol *</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion"></textarea>
    </div>

    <button type="submit" style="width: 100%;">Guardar Rol</button>
</form>

<?php include("../includes/footer.php"); ?>
