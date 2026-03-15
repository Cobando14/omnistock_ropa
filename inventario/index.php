<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Inventario";
include("../includes/header.php");

// Filtros
$busqueda = $_GET['busqueda'] ?? '';
$filtro_producto = $_GET['producto'] ?? '';
$orden = $_GET['orden'] ?? 'producto';

// lista de productos para filtro
$productos_filter = $conn->query("SELECT DISTINCT p.id_producto, p.nom_producto 
FROM productos p 
JOIN inventario i ON p.id_producto = i.producto 
ORDER BY p.nom_producto ASC");

// query principal
$sql = "SELECT 
    p.id_producto,
    p.nom_producto,
    p.precio_venta,
    i.talla,
    i.color,
    t.nombre_talla,
    c.nombre_color,
    i.stock,
    COALESCE(SUM(dv.cantidad), 0) as cantidad_vendida,
    i.stock + COALESCE(SUM(dv.cantidad), 0) as stock_original
FROM inventario i
JOIN productos p ON i.producto = p.id_producto
LEFT JOIN tallas t ON i.talla = t.id_talla
LEFT JOIN colores c ON i.color = c.id_color
LEFT JOIN detalle_venta dv ON i.id_inventario = dv.inventario
WHERE 1=1";

if(!empty($busqueda)){
    $busqueda = $conn->real_escape_string($busqueda);
    $sql .= " AND (p.nom_producto LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%')";
}
if(!empty($filtro_producto)){
    $filtro_producto = intval($filtro_producto);
    $sql .= " AND p.id_producto = $filtro_producto";
}
$sql .= " GROUP BY i.id_inventario, p.id_producto, i.talla, i.color ";

switch($orden){
    case 'stock_bajo':
        $sql .= "ORDER BY i.stock ASC";
        break;
    case 'stock_alto':
        $sql .= "ORDER BY i.stock DESC";
        break;
    case 'vendidas':
        $sql .= "ORDER BY cantidad_vendida DESC";
        break;
    case 'precio':
        $sql .= "ORDER BY p.precio_venta DESC";
        break;
    default:
        $sql .= "ORDER BY p.nom_producto ASC, t.nombre_talla ASC";
}
$result = $conn->query($sql);
?>

<div class="container">
<div class="page-header">
    <h2>Gestionar Inventario (200 productos)</h2>
    <p style="color: #6b7280;">Control de stock, ventas y devoluciones</p>
    <?php if($result->num_rows > 0): ?>
    <p style="color: #374151; font-weight: 500; margin-top: 0.5rem;">
        📊 Mostrando <?php echo $result->num_rows; ?> item(s) de inventario
        <?php if(!empty($busqueda) || !empty($filtro_producto)): ?>
            (filtrados)
        <?php endif; ?>
    </p>
    <?php endif; ?>
</div>

<?php if($result->num_rows > 0): ?>
<style>
    .filter-tag,
    .stat-card {
        transition: none;
    }
    
    .btn-primary:hover,
    .btn-secondary:hover,
    .filter-group input:focus,
    .filter-group select:focus {
        transform: none;
    }
    
    @media (max-width: 1024px) {
        .container {
            padding: 1rem;
        }

        .page-header h2 {
            font-size: 1.75rem;
        }

        .stats {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .inventario-filters {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filters-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            padding-bottom: 1rem;
        }

        .filters-header h3 {
            font-size: 1.25rem;
        }

        .filters-header-icon {
            font-size: 1.25rem;
        }

        .filters-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            position: relative;
            min-width: 0; /* ensure flex/grid items can shrink */
        }

        .filter-group input,
        .filter-group select {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .filter-group input[name="busqueda"] {
            min-width: unset;
            background-size: 1rem;
            background-position: right 0.5rem center;
            padding-right: 2rem;
        }

        .filter-actions {
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }

        .active-filters {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .active-filters-title {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .filter-tags {
            gap: 0.5rem;
        }

        .filter-tag {
            font-size: 0.8rem;
            padding: 0.25rem 0.625rem;
        }

        .stats {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .inventario-table {
            font-size: 0.85rem;
            border-radius: 8px;
        }

        .inventario-table th,
        .inventario-table td {
            padding: 0.5rem 0.75rem;
        }

        .inventario-table th {
            font-size: 0.8rem;
        }

        .filter-label {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .filter-icon {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 640px) {
        .page-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .page-header p {
            font-size: 0.9rem;
        }

        .stats {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-card {
            padding: 0.875rem;
            border-radius: 8px;
        }

        .stat-label {
            font-size: 0.8rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        .inventario-filters {
            padding: 1.25rem;
            border-radius: 10px;
        }

        .filters-header h3 {
            font-size: 1.125rem;
        }

        .filters-grid {
            gap: 1.25rem;
        }

        .filter-group input,
        .filter-group select {
            padding: 0.625rem;
        }

        .active-filters {
            padding: 0.875rem;
        }

        .filter-tags {
            flex-wrap: wrap;
            gap: 0.375rem;
        }

        .filter-tag {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0.75rem;
        }

        .page-header h2 {
            font-size: 1.375rem;
        }

        .inventario-table th:nth-child(6),
        .inventario-table td:nth-child(6),
        .inventario-table th:nth-child(7),
        .inventario-table td:nth-child(7) {
            display: none;
        }

        .inventario-table th:nth-child(8),
        .inventario-table td:nth-child(8) {
            text-align: center;
        }

        .inventario-filters {
            padding: 1rem;
        }

        .filters-header {
            padding-bottom: 0.75rem;
        }

        .filters-header h3 {
            font-size: 1rem;
        }

        .filters-header-icon {
            font-size: 1rem;
        }

        .btn-primary, .btn-secondary {
            padding: 0.625rem 1.25rem;
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
        }

        .stock-bajo, .stock-medio, .stock-bien {
            font-weight: 700;
        }
    }

    @media (max-width: 360px) {
        .inventario-table th:nth-child(4),
        .inventario-table td:nth-child(4) {
            display: none;
        }

        .inventario-table th, .inventario-table td {
            padding: 0.375rem 0.5rem;
        }

        .inventario-table {
            font-size: 0.8rem;
        }

        .stat-card {
            padding: 0.75rem;
        }

        .stat-value {
            font-size: 1.125rem;
        }
    }
</style>
</style>

<?php
// Calcular estadísticas
$stats_result = $conn->query("
SELECT 
    COUNT(DISTINCT id_inventario) as total_items,
    SUM(stock) as stock_total,
    COUNT(CASE WHEN stock < 10 THEN 1 END) as items_bajo_stock,
    COALESCE(SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END), 0) as items_agotados
FROM inventario
");
$stats = $stats_result->fetch_assoc();
?>

<div class="stats">
    <div class="stat-card">
        <div class="stat-label">Total de Items</div>
        <div class="stat-value"><?php echo $stats['total_items']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Stock Total</div>
        <div class="stat-value"><?php echo number_format($stats['stock_total']); ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #ef4444;">
        <div class="stat-label">Items Bajo Stock</div>
        <div class="stat-value"><?php echo $stats['items_bajo_stock']; ?></div>
    </div>
    <div class="stat-card" style="border-left-color: #dc2626;">
        <div class="stat-label">Items Agotados</div>
        <div class="stat-value"><?php echo $stats['items_agotados']; ?></div>
    </div>
</div>

<div class="inventario-filters">
    <div class="filters-header">
        <span class="filters-header-icon">🔍</span>
        <h3>Filtros de Búsqueda</h3>
    </div>
    
    <?php if(!empty($busqueda) || !empty($filtro_producto) || $orden !== 'producto'): ?>
    <div class="active-filters">
        <div class="active-filters-title">
            <span>🎯</span>
            Filtros Activos
        </div>
        <div class="filter-tags">
            <?php if(!empty($busqueda)): ?>
                <span class="filter-tag">
                    <span class="filter-tag-icon">🔍</span>
                    Búsqueda: "<?php echo htmlspecialchars($busqueda); ?>"
                </span>
            <?php endif; ?>
            <?php if(!empty($filtro_producto)): 
                $prod_name = '';
                $productos_filter->data_seek(0);
                while($row = $productos_filter->fetch_assoc()){
                    if($row['id_producto'] == $filtro_producto){
                        $prod_name = $row['nom_producto'];
                        break;
                    }
                }
            ?>
                <span class="filter-tag">
                    <span class="filter-tag-icon">📦</span>
                    Producto: <?php echo htmlspecialchars($prod_name); ?>
                </span>
            <?php endif; ?>
            <?php if($orden !== 'producto'): 
                $orden_labels = [
                    'stock_bajo' => 'Stock más bajo',
                    'stock_alto' => 'Stock más alto', 
                    'vendidas' => 'Más vendidas',
                    'precio' => 'Precio más alto'
                ];
                $orden_icons = [
                    'stock_bajo' => '📉',
                    'stock_alto' => '📈', 
                    'vendidas' => '💰',
                    'precio' => '💎'
                ];
            ?>
                <span class="filter-tag">
                    <span class="filter-tag-icon"><?php echo $orden_icons[$orden] ?? '🔄'; ?></span>
                    Orden: <?php echo $orden_labels[$orden] ?? $orden; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <form method="GET" class="filters-grid">
        <div class="filter-group">
            <label class="filter-label">
                <span class="filter-icon">🔍</span>
                Buscar Producto
            </label>
            <input type="text" name="busqueda" placeholder="Nombre o descripción del producto..." value="<?php echo htmlspecialchars($busqueda); ?>">
        </div>
        
        <div class="filter-group">
            <label class="filter-label">
                <span class="filter-icon">📦</span>
                Filtrar por Producto Específico
            </label>
            <select name="producto">
                <option value="">Todos los productos</option>
                <?php 
                $productos_filter->data_seek(0);
                while($row = $productos_filter->fetch_assoc()):
                ?>
                    <option value="<?php echo $row['id_producto']; ?>" <?php echo $filtro_producto == $row['id_producto'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nom_producto']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">
                <span class="filter-icon">🔄</span>
                Ordenar Por
            </label>
            <select name="orden">
                <option value="producto" <?php echo $orden == 'producto' ? 'selected' : ''; ?>>Producto (A-Z)</option>
                <option value="stock_bajo" <?php echo $orden == 'stock_bajo' ? 'selected' : ''; ?>>Stock más bajo</option>
                <option value="stock_alto" <?php echo $orden == 'stock_alto' ? 'selected' : ''; ?>>Stock más alto</option>
                <option value="vendidas" <?php echo $orden == 'vendidas' ? 'selected' : ''; ?>>Más vendidas</option>
                <option value="precio" <?php echo $orden == 'precio' ? 'selected' : ''; ?>>Precio más alto</option>
            </select>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn-primary">
                <span>🔍</span>
                Buscar
            </button>
            <a href="index.php" class="btn-secondary" style="text-decoration: none;">
                <span>🗑️</span>
                Limpiar
            </a>
        </div>
    </form>
</div>

<table class="inventario-table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Talla</th>
            <th>Color</th>
            <th style="text-align: right;">Precio</th>
            <th style="text-align: right;">Stock Actual</th>
            <th style="text-align: right;">Vendidas</th>
            <th style="text-align: right;">Stock Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($result->num_rows == 0):
        ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 2rem;">No hay productos que coincidan con los filtros</td>
            </tr>
        <?php 
        else:
            while($row = $result->fetch_assoc()):
                $stock_clase = '';
                if($row['stock'] == 0){
                    $stock_clase = 'stock-bajo';
                } elseif($row['stock'] < 10){
                    $stock_clase = 'stock-medio';
                } else {
                    $stock_clase = 'stock-bien';
                }
        ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['nom_producto']); ?></strong></td>
                <td><span class="badge badge-talla"><?php echo htmlspecialchars($row['nombre_talla'] ?? 'N/A'); ?></span></td>
                <td><span class="badge badge-color"><?php echo htmlspecialchars($row['nombre_color'] ?? 'N/A'); ?></span></td>
                <td style="text-align: right;">$<?php echo number_format($row['precio_venta'], 0); ?></td>
                <td style="text-align: right;" class="<?php echo $stock_clase; ?>"><?php echo $row['stock']; ?></td>
                <td style="text-align: right; color: #10b981; font-weight: 600;"><?php echo $row['cantidad_vendida']; ?></td>
                <td style="text-align: right; color: #6b7280;"><?php echo $row['stock_original']; ?></td>
                <td>
                    <a href="detalle.php?id=<?php echo $row['id_producto']; ?>" style="color: #2563eb; text-decoration: none; font-weight: 500;">Ver</a>
                </td>
            </tr>
        <?php 
            endwhile;
        endif;
        ?>
    </tbody>
</table>

<?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
