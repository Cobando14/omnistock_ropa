<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Reporte de Inventario";
include("../includes/header.php");
?>

<div class="page-header">
    <h2>Reporte de Inventario</h2>
    <p style="color: #6b7280;">Análisis estadístico del movimiento de inventario</p>
</div>

<style>
    .reportes-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .reporte-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .reporte-card h3 {
        margin-top: 0;
        color: #1f2937;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 1rem;
    }
    
    .reporte-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .reporte-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .reporte-list li:last-child {
        border-bottom: none;
    }
    
    .reporte-name {
        flex: 1;
        font-weight: 500;
        color: #1f2937;
    }
    
    .reporte-value {
        font-weight: 700;
        color: #2563eb;
        margin-left: 1rem;
        text-align: right;
    }
    
    .reporte-value.danger {
        color: #ef4444;
    }
    
    .reporte-value.success {
        color: #10b981;
    }
    
    .reporte-table {
        width: 100%;
        margin-top: 1.5rem;
    }
    
    .reporte-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .reporte-table thead {
        background-color: #f3f4f6;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .reporte-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
    }
    
    .reporte-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .reporte-table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .progress-bar {
        display: inline-block;
        width: 60px;
        height: 4px;
        background-color: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-right: 0.5rem;
        vertical-align: middle;
    }
    
    .progress-bar-fill {
        height: 100%;
        background-color: #10b981;
    }
    
    @media (max-width: 768px) {
        .reportes-container {
            grid-template-columns: 1fr;
        }
        
        .reporte-table {
            overflow-x: auto;
        }
        
        .reporte-table table {
            font-size: 0.9rem;
        }
    }
</style>

<div class="reportes-container">
    <?php 
    // Reporte 1: Productos más vendidos
    $top_vendidos = $conn->query("
        SELECT 
            p.nom_producto,
            SUM(dv.cantidad) as total_vendido,
            COUNT(DISTINCT v.id_venta) as num_ventas
        FROM detalle_venta dv
        JOIN inventario i ON dv.inventario = i.id_inventario
        JOIN productos p ON i.producto = p.id_producto
        JOIN ventas v ON dv.venta = v.id_venta
        GROUP BY p.id_producto, p.nom_producto
        ORDER BY total_vendido DESC
        LIMIT 10
    ");
    ?>
    
    <div class="reporte-card">
        <h3>Top 10 Productos Más Vendidos</h3>
        <ul class="reporte-list">
            <?php 
            if($top_vendidos->num_rows == 0):
            ?>
                <li style="padding: 1rem; text-align: center; color: #6b7280;">Sin ventas aún</li>
            <?php 
            else:
                $posicion = 1;
                while($row = $top_vendidos->fetch_assoc()):
            ?>
                <li>
                    <span class="reporte-name"><?php echo $posicion . '. ' . htmlspecialchars($row['nom_producto']); ?></span>
                    <span class="reporte-value success"><?php echo number_format($row['total_vendido']); ?> un.</span>
                </li>
            <?php 
                    $posicion++;
                endwhile;
            endif;
            ?>
        </ul>
    </div>
    
    <?php 
    // Reporte 2: Productos con bajo stock
    $bajo_stock = $conn->query("
        SELECT 
            p.nom_producto,
            i.stock,
            t.nombre_talla,
            c.nombre_color
        FROM inventario i
        JOIN productos p ON i.producto = p.id_producto
        LEFT JOIN tallas t ON i.talla = t.id_talla
        LEFT JOIN colores c ON i.color = c.id_color
        WHERE i.stock < 15
        ORDER BY i.stock ASC
        LIMIT 10
    ");
    ?>
    
    <div class="reporte-card">
        <h3>Top 10 Items Bajo Stock</h3>
        <ul class="reporte-list">
            <?php 
            if($bajo_stock->num_rows == 0):
            ?>
                <li style="padding: 1rem; text-align: center; color: #6b7280;">Inventario bien abastecido</li>
            <?php 
            else:
                while($row = $bajo_stock->fetch_assoc()):
            ?>
                <li>
                    <span class="reporte-name">
                        <?php echo htmlspecialchars($row['nom_producto']); ?>
                        <br><span style="font-size: 0.85rem; color: #6b7280;"><?php echo htmlspecialchars($row['nombre_talla'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($row['nombre_color'] ?? 'N/A'); ?></span>
                    </span>
                    <span class="reporte-value danger"><?php echo $row['stock']; ?> un.</span>
                </li>
            <?php 
                endwhile;
            endif;
            ?>
        </ul>
    </div>
    
    <?php 
    // Reporte 3: Resumen de movimiento
    $movimiento = $conn->query("
        SELECT 
            COUNT(DISTINCT i.id_inventario) as total_items,
            SUM(i.stock) as stock_actual,
            COALESCE(SUM(dv.cantidad), 0) as total_vendido,
            COUNT(CASE WHEN i.stock = 0 THEN 1 END) as items_agotados,
            COUNT(CASE WHEN i.stock < 10 THEN 1 END) as items_criticos
        FROM inventario i
        LEFT JOIN detalle_venta dv ON i.id_inventario = dv.inventario
    ");
    $mov = $movimiento->fetch_assoc();
    ?>
    
    <div class="reporte-card">
        <h3>Resumen de Movimiento</h3>
        <ul class="reporte-list">
            <li>
                <span class="reporte-name">Total de Items</span>
                <span class="reporte-value"><?php echo number_format($mov['total_items']); ?></span>
            </li>
            <li>
                <span class="reporte-name">Stock Actual</span>
                <span class="reporte-value"><?php echo number_format($mov['stock_actual']); ?></span>
            </li>
            <li>
                <span class="reporte-name">Total Vendido</span>
                <span class="reporte-value success"><?php echo number_format($mov['total_vendido']); ?></span>
            </li>
            <li>
                <span class="reporte-name">Items Agotados</span>
                <span class="reporte-value danger"><?php echo number_format($mov['items_agotados']); ?></span>
            </li>
            <li>
                <span class="reporte-name">Items Críticos (&lt; 10)</span>
                <span class="reporte-value danger"><?php echo number_format($mov['items_criticos']); ?></span>
            </li>
        </ul>
    </div>
</div>

<?php 
// Tabla: Movimiento por producto
$movimiento_producto = $conn->query("
    SELECT 
        p.nom_producto,
        COUNT(DISTINCT i.id_inventario) as variantes,
        SUM(i.stock) as stock_actual,
        COALESCE(SUM(dv.cantidad), 0) as total_vendido,
        SUM(i.stock) + COALESCE(SUM(dv.cantidad), 0) as stock_original,
        CASE 
            WHEN SUM(i.stock) + COALESCE(SUM(dv.cantidad), 0) = 0 THEN 0
            ELSE ROUND((COALESCE(SUM(dv.cantidad), 0) / (SUM(i.stock) + COALESCE(SUM(dv.cantidad), 0))) * 100)
        END as porcentaje_vendido
    FROM productos p
    JOIN inventario i ON p.id_producto = i.producto
    LEFT JOIN detalle_venta dv ON i.id_inventario = dv.inventario
    GROUP BY p.id_producto, p.nom_producto
    ORDER BY total_vendido DESC
");
?>

<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);">
    <h3 style="margin-top: 0;">Movimiento de Inventario por Producto</h3>
    
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
            <tr>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151;">Producto</th>
                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Variantes</th>
                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Stock Actual</th>
                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Vendidas</th>
                <th style="padding: 1rem; text-align: right; font-weight: 600; color: #374151;">Total Hist.</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151;">Mov.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while($row = $movimiento_producto->fetch_assoc()):
            ?>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem 1rem; font-weight: 500;"><?php echo htmlspecialchars($row['nom_producto']); ?></td>
                    <td style="padding: 0.75rem 1rem; text-align: right;"><?php echo $row['variantes']; ?></td>
                    <td style="padding: 0.75rem 1rem; text-align: right;"><?php echo number_format($row['stock_actual']); ?></td>
                    <td style="padding: 0.75rem 1rem; text-align: right; color: #10b981; font-weight: 600;"><?php echo number_format($row['total_vendido']); ?></td>
                    <td style="padding: 0.75rem 1rem; text-align: right; color: #6b7280;"><?php echo number_format($row['stock_original']); ?></td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 60px; height: 4px; background-color: #e5e7eb; border-radius: 2px; overflow: hidden;">
                                <div style="height: 100%; background-color: #10b981; width: <?php echo $row['porcentaje_vendido']; ?>%;"></div>
                            </div>
                            <span style="font-size: 0.85rem; color: #6b7280; min-width: 35px;"><?php echo $row['porcentaje_vendido']; ?>%</span>
                        </div>
                    </td>
                </tr>
            <?php 
            endwhile;
            ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
