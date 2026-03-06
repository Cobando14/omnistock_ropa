<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$page_title = "Editar Categoría";
include("../includes/header.php");

$sql = "SELECT * FROM categoria WHERE id_categoria=$id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo '<div class="alert alert-danger">Categoría no encontrada</div>';
    include("../includes/footer.php");
    exit;
}

$categoria = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "UPDATE categoria SET
    nom_categoria='$nombre',
    desc_categoria='$descripcion'
    WHERE id_categoria=$id";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<div class="page-header">
    <h2>Editar Categoría</h2>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre de la Categoría *</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($categoria['nom_categoria'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($categoria['desc_categoria'] ?? ''); ?></textarea>
    </div>

    <div style="display: flex; gap: 1rem;">
        <button type="submit" style="flex: 1;">Actualizar Categoría</button>
        <a href="index.php" class="btn" style="flex: 1; text-align: center; text-decoration: none; background-color: #6b7280;">Cancelar</a>
    </div>
</form>

<?php include("../includes/footer.php"); ?>
