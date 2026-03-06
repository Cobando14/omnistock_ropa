<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id_producto = intval($_GET['id']);
$page_title = "Detalle de Producto";
include("../includes/header.php");

// Obtener datos del producto
$producto_sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
$producto_result = $conn->query($producto_sql);

if($producto_result->num_rows == 0){
    echo '<div class="alert alert-danger">Producto no encontrado</div>';
    include("../includes/footer.php");
    exit;
}

$producto = $producto_result->fetch_assoc();

// Obtener todas las variantes del producto
$variantes_sql = "SELECT 
    i.id_inventario,
    i.stock,
    t.nombre_talla,
    c.nombre_color,
    COALESCE(SUM(dv.cantidad), 0) as cantidad_vendida,
    i.stock + COALESCE(SUM(dv.cantidad), 0) as stock_original
FROM inventario i
LEFT JOIN tallas t ON i.talla = t.id_talla
LEFT JOIN colores c ON i.color = c.id_color
LEFT JOIN detalle_venta dv ON i.id_inventario = dv.inventario
WHERE i.producto = $id_producto
GROUP BY i.id_inventario, i.stock, t.nombre_talla, c.nombre_color
ORDER BY t.nombre_talla ASC, c.nombre_color ASC";

$variantes_result = $conn->query($variantes_sql);

// Obtener historial de ventas
$ventas_sql = "SELECT 
    v.id_venta,
    v.fecha,
    dv.cantidad,
    dv.precio,
    dv.subtotal,
    u.nom_usuario,
    c.nombre as cliente_nombre
FROM detalle_venta dv
JOIN ventas v ON dv.venta = v.id_venta
JOIN inventario i ON dv.inventario = i.id_inventario
LEFT JOIN usuarios u ON v.usuario = u.id_usuario
LEFT JOIN clientes c ON v.cliente = c.id_cliente
WHERE i.producto = $id_producto
ORDER BY v.fecha DESC
LIMIT 20";

$ventas_result = $conn->query($ventas_sql);
?>

<style>
    .detalle-header {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .detalle-header h2 {
        margin-top: 0;
        color: #1f2937;
    }
    
    .detalle-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-top: 1.5rem;
    }
    
    .info-item {
        border-left: 4px solid #2563eb;
        padding-left: 1rem;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1.25rem;
        color: #1f2937;
        font-weight: 600;
    }
    
    .variantes-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .variantes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .variante-card {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 1rem;
        background: #f9fafb;
    }
    
    .variante-header {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .variante-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background-color: #dbeafe;
        color: #0c4a6e;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .variante-badge.color {
        background-color: #e5e7eb;
        color: #374151;
    }
    
    .variante-stats {
        display: flex;
        justify-content: space-between;
        margin: 1rem 0;
        padding: 0.75rem 0;
        border-top: 1px solid #d1d5db;
        border-bottom: 1px solid #d1d5db;
    }
    
    .variante-stat {
        text-align: center;
    }
    
    .variante-stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
    }
    
    .variante-stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .ventas-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .ventas-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
    }
    
    .ventas-table thead {
        background-color: #f3f4f6;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .ventas-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
    }
    
    .ventas-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .ventas-table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .back-link {
        display: inline-block;
        margin-bottom: 1.5rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }
    
    .back-link:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .detalle-info {
            grid-template-columns: 1fr;
        }
        
        .variantes-grid {
            grid-template-columns: 1fr;
        }
        
        .ventas-table {
            font-size: 0.85rem;
        }
        
        .ventas-table th,
        .ventas-table td {
            padding: 0.5rem;
        }
    }
</style>

<a href="index.php" class="back-link">← Volver al inventario</a>

<div class="detalle-header">
    <h2><?php echo htmlspecialchars($producto['nom_producto']); ?></h2>
    <p style="color: #6b7280; margin: 0.5rem 0 0 0;"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></p>
    
    <div class="detalle-info">
        <div class="info-item">
            <div class="info-label">Precio de Venta</div>
            <div class="info-value">$<?php echo number_format($producto['precio_venta'], 0); ?></div>
        </div>
        
        <?php 
        // Calcular totales para este producto
        $totales_sql = "SELECT 
            COUNT(DISTINCT i.id_inventario) as total_variantes,
            SUM(i.stock) as stock_total,
            COALESCE(SUM(dv.cantidad), 0) as vendidas_total
        FROM inventario i
        LEFT JOIN detalle_venta dv ON i.id_inventario = dv.inventario
        WHERE i.producto = $id_producto";
        
        $totales_result = $conn->query($totales_sql);
        $totales = $totales_result->fetch_assoc();
        ?>
        
        <div class="info-item">
            <div class="info-label">Total de Variantes</div>
            <div class="info-value"><?php echo $totales['total_variantes']; ?></div>
        </div>
        
        <div class="info-item">
            <div class="info-label">Stock Total</div>
            <div class="info-value"><?php echo number_format($totales['stock_total']); ?></div>
        </div>
        
        <div class="info-item" style="border-left-color: #10b981;">
            <div class="info-label">Unidades Vendidas</div>
            <div class="info-value" style="color: #10b981;"><?php echo number_format($totales['vendidas_total']); ?></div>
        </div>
    </div>
</div>

<div class="variantes-section">
    <h3>Variantes (Talles × Colores)</h3>
    
    <div class="variantes-grid">
        <?php 
        if($variantes_result->num_rows == 0):
        ?>
            <p style="color: #6b7280;">No hay variantes registradas para este producto</p>
        <?php 
        else:
            while($variante = $variantes_result->fetch_assoc()):
        ?>
            <div class="variante-card">
                <div class="variante-header">
                    <span class="variante-badge"><?php echo htmlspecialchars($variante['nombre_talla'] ?? 'N/A'); ?></span>
                    <span class="variante-badge color"><?php echo htmlspecialchars($variante['nombre_color'] ?? 'N/A'); ?></span>
                </div>
                
                <div class="variante-stats">
                    <div class="variante-stat">
                        <div class="variante-stat-label">Stock</div>
                        <div class="variante-stat-value"><?php echo $variante['stock']; ?></div>
                    </div>
                    <div class="variante-stat">
                        <div class="variante-stat-label">Vendidas</div>
                        <div class="variante-stat-value" style="color: #10b981;"><?php echo $variante['cantidad_vendida']; ?></div>
                    </div>
                    <div class="variante-stat">
                        <div class="variante-stat-label">Total</div>
                        <div class="variante-stat-value"><?php echo $variante['stock_original']; ?></div>
                    </div>
                </div>
                
                <?php 
                $porcentaje_vendido = $variante['stock_original'] > 0 
                    ? round(($variante['cantidad_vendida'] / $variante['stock_original']) * 100) 
                    : 0;
                ?>
                <div style="margin-top: 1rem;">
                    <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.25rem;">Movimiento</div>
                    <div style="background: #e5e7eb; height: 6px; border-radius: 3px; overflow: hidden;">
                        <div style="background: #10b981; height: 100%; width: <?php echo $porcentaje_vendido; ?>%;"></div>
                    </div>
                    <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; text-align: right;">
                        <?php echo $porcentaje_vendido; ?>% vendido
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        endif;
        ?>
    </div>
</div>

<div class="ventas-section">
    <h3>Últimas Ventas (Últimas 20)</h3>
    
    <?php 
    if($ventas_result->num_rows == 0):
    ?>
        <p style="color: #6b7280; margin-top: 1rem;">Este producto aún no ha sido vendido</p>
    <?php 
    else:
    ?>
        <table class="ventas-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th style="text-align: right;">Cantidad</th>
                    <th style="text-align: right;">Precio Unitario</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $ventas_result->data_seek(0);
                while($venta = $ventas_result->fetch_assoc()):
                ?>
                    <tr>
                        <td>
                            <span style="color: #6b7280;">
                                <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($venta['cliente_nombre'] ?? 'Sin cliente'); ?></td>
                        <td><?php echo htmlspecialchars($venta['nom_usuario'] ?? 'N/A'); ?></td>
                        <td style="text-align: right; font-weight: 600;"><?php echo $venta['cantidad']; ?></td>
                        <td style="text-align: right;">$<?php echo number_format($venta['precio'], 0); ?></td>
                        <td style="text-align: right; font-weight: 600; color: #10b981;">$<?php echo number_format($venta['subtotal'], 0); ?></td>
                    </tr>
                <?php 
                endwhile;
                ?>
            </tbody>
        </table>
    <?php 
    endif;
    ?>
</div>

<?php include("../includes/footer.php"); ?>
