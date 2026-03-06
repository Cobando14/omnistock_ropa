<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Crear Producto";
include("../includes/header.php");

if($_POST){
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
    $precio_venta = floatval($_POST['precio_venta']);
    $precio_compra = floatval($_POST['precio_compra']);
    $categoria = intval($_POST['categoria'] ?? 0);

    $sql = "INSERT INTO productos (nom_producto, descripcion, precio_venta, precio_compra, categoria)
    VALUES ('$nombre', '$descripcion', $precio_venta, $precio_compra, " . ($categoria > 0 ? $categoria : "NULL") . ")";

    if($conn->query($sql)){
        header("Location: index.php?success=1");
        exit;
    } else {
        $error = "Error al crear el producto: " . $conn->error;
    }
}

$categorias_result = $conn->query("SELECT id_categoria, nom_categoria FROM categoria ORDER BY nom_categoria ASC");
?>

<div class="page-header">
    <h2>Crear Nuevo Producto</h2>
    <p style="color: #6b7280;">Completa el formulario para agregar un nuevo producto</p>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="nombre">Nombre del Producto *</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion"></textarea>
    </div>

    <div class="form-group">
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria">
            <option value="">Sin categoría</option>
            <?php while($row = $categorias_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id_categoria']; ?>"><?php echo htmlspecialchars($row['nom_categoria']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="precio_venta">Precio de Venta *</label>
        <input type="number" id="precio_venta" name="precio_venta" step="0.01" min="0" required>
    </div>

    <div class="form-group">
        <label for="precio_compra">Precio de Compra *</label>
        <input type="number" id="precio_compra" name="precio_compra" step="0.01" min="0" required>
    </div>

    <button type="submit" style="width: 100%;">Guardar Producto</button>
</form>

<?php include("../includes/footer.php"); ?>
