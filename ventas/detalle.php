<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id_venta = intval($_GET['id']);
$page_title = "Detalle de Venta";
include("../includes/header.php");

// Obtener datos de venta
$sql = "SELECT v.id_venta, v.fecha, c.nombre, c.correo, c.telefono, 
u.nom_usuario, u.apell_usuario, v.total
FROM ventas v
LEFT JOIN clientes c ON v.cliente = c.id_cliente
LEFT JOIN usuarios u ON v.usuario = u.id_usuario
WHERE v.id_venta = $id_venta";

$result = $conn->query($sql);

if($result->num_rows == 0){
    echo '<div class="alert alert-danger">Venta no encontrada</div>';
    include("../includes/footer.php");
    exit;
}

$venta = $result->fetch_assoc();

// Obtener detalles de venta
$sql_detalles = "SELECT dv.id_detalle, p.nom_producto, t.nombre_talla, c.nombre_color, dv.cantidad, dv.precio, dv.subtotal
FROM detalle_venta dv
JOIN inventario i ON dv.inventario = i.id_inventario
JOIN productos p ON i.producto = p.id_producto
LEFT JOIN tallas t ON i.talla = t.id_talla
LEFT JOIN colores c ON i.color = c.id_color
WHERE dv.venta = $id_venta
ORDER BY dv.id_detalle ASC";

$result_detalles = $conn->query($sql_detalles);

// Calcular IVA y subtotal
$subtotal = 0;
$iva = 0;
if($result_detalles->num_rows > 0){
    $result_detalles->data_seek(0);
    while($row = $result_detalles->fetch_assoc()){
        $subtotal += $row['subtotal'];
    }
    $iva = $subtotal * 0.19;
}
?>

<style>
    .venta-detalle {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .encabezado {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 2rem;
    }
    
    .bloque-info h4 {
        color: #2563eb;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .bloque-info p {
        margin: 0.3rem 0;
        color: #374151;
    }
    
    .resumen {
        background: #f3f4f6;
        padding: 1.5rem;
        border-radius: 6px;
        margin: 2rem 0;
        max-width: 400px;
        margin-left: auto;
    }
    
    .resumen-fila {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }
    
    .resumen-fila.total {
        border-top: 2px solid #e5e7eb;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: #2563eb;
    }
    
    .resumen-fila.iva {
        color: #ef4444;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .encabezado {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="venta-detalle">
    <div class="encabezado">
        <div>
            <h2 style="margin: 0 0 1rem 0; color: #111827;">Venta #<?php echo $venta['id_venta']; ?></h2>
            <div class="bloque-info">
                <h4>Información de Venta</h4>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s', strtotime($venta['fecha'])); ?></p>
                <p><strong>Vendedor:</strong> <?php echo htmlspecialchars($venta['nom_usuario'] . ' ' . ($venta['apell_usuario'] ?? '')); ?></p>
            </div>
        </div>
        
        <div>
            <div class="bloque-info">
                <h4>Datos del Cliente</h4>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($venta['nombre'] ?? 'No registrado'); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($venta['telefono'] ?? '-'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($venta['correo'] ?? '-'); ?></p>
            </div>
        </div>
    </div>
    
    <h3 style="margin-top: 2rem;">Productos</h3>
    
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Color</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $result_detalles->data_seek(0);
            while($row = $result_detalles->fetch_assoc()): 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nom_producto']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_talla'] ?? 'S/T'); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_color'] ?? 'S/C'); ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td>$<?php echo number_format($row['precio'], 2); ?></td>
                <td>$<?php echo number_format($row['subtotal'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="resumen">
        <div class="resumen-fila">
            <span>Subtotal:</span>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="resumen-fila iva">
            <span>IVA (19%):</span>
            <span>$<?php echo number_format($iva, 2); ?></span>
        </div>
        <div class="resumen-fila total">
            <span>Total de Venta:</span>
            <span>$<?php echo number_format($venta['total'], 2); ?></span>
        </div>
    </div>
    
    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        <a href="index.php" class="btn" style="flex: 1; text-align: center; text-decoration: none;">← Volver</a>
        <a href="eliminar.php?id=<?php echo $venta['id_venta']; ?>" class="btn" style="flex: 1; text-align: center; text-decoration: none; background-color: #ef4444;" onclick="return confirm('¿Está seguro de eliminar esta venta?')">🗑️ Eliminar</a>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
