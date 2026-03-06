<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$page_title = "Editar Producto";
include("../includes/header.php");

$sql = "SELECT * FROM productos WHERE id_producto=$id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo '<div class="alert alert-danger">Producto no encontrado</div>';
    include("../includes/footer.php");
    exit;
}

$producto = $result->fetch_assoc();
$categorias_result = $conn->query("SELECT id_categoria, nom_categoria FROM categoria ORDER BY nom_categoria ASC");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio_venta = floatval($_POST['precio_venta']);
    $precio_compra = floatval($_POST['precio_compra']);
    $categoria = intval($_POST['categoria'] ?? 0);

    $sql = "UPDATE productos SET
    nom_producto='$nombre',
    descripcion='$descripcion',
    precio_venta=$precio_venta,
    precio_compra=$precio_compra,
    categoria=" . ($categoria > 0 ? $categoria : "NULL") . "
    WHERE id_producto=$id";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit();
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<div class="page-header">
    <h2>Editar Producto</h2>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre del Producto *</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nom_producto'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria">
            <option value="">Sin categoría</option>
            <?php while($row = $categorias_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id_categoria']; ?>" <?php if(($producto['categoria'] ?? null) == $row['id_categoria']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['nom_categoria']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="precio_venta">Precio de Venta *</label>
        <input type="number" id="precio_venta" name="precio_venta" value="<?php echo $producto['precio_venta'] ?? 0; ?>" step="0.01" min="0" required>
    </div>

    <div class="form-group">
        <label for="precio_compra">Precio de Compra *</label>
        <input type="number" id="precio_compra" name="precio_compra" value="<?php echo $producto['precio_compra'] ?? 0; ?>" step="0.01" min="0" required>
    </div>

    <div style="display: flex; gap: 1rem;">
        <button type="submit" style="flex: 1;">Actualizar Producto</button>
        <a href="index.php" class="btn" style="flex: 1; text-align: center; text-decoration: none; background-color: #6b7280;">Cancelar</a>
    </div>
</form>

<?php include("../includes/footer.php"); ?>
