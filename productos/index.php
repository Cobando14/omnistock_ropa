<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Productos";
include("../includes/header.php");

$sql = "SELECT p.id_producto, p.nom_producto, p.descripcion, p.precio_venta, p.precio_compra, c.nom_categoria
FROM productos p
LEFT JOIN categoria c ON p.categoria = c.id_categoria
ORDER BY p.nom_producto ASC";

$result = $conn->query($sql);
?>

<style>
/* Modern Product Management Styles */
:root {
    --primary-color: #3b82f6;
    --primary-dark: #2563eb;
    --primary-light: #dbeafe;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --border-radius: 8px;
    --border-radius-lg: 12px;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.action-section {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-primary:active {
    transform: translateY(0);
}

.data-table-container {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.data-table thead {
    background: var(--gray-50);
    border-bottom: 2px solid var(--gray-200);
}

.data-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-200);
    position: sticky;
    top: 0;
    background: var(--gray-50);
    z-index: 10;
}

.data-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
    vertical-align: middle;
}

.data-table tbody tr {
    transition: all 0.2s ease;
}

.data-table tbody tr:hover {
    background: var(--gray-50);
    transform: scale(1.01);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.product-name {
    font-weight: 600;
    color: var(--gray-900);
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-description {
    color: var(--gray-600);
    max-width: 300px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--primary-light);
    color: var(--primary-dark);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.price-cell {
    font-weight: 600;
    font-size: 0.875rem;
}

.price-sale {
    color: var(--success-color);
}

.price-cost {
    color: var(--gray-500);
    font-size: 0.8rem;
}

.actions-cell {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    align-items: center;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: var(--warning-color);
    color: white;
}

.btn-edit:hover {
    background: #d97706;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.btn-delete {
    background: var(--danger-color);
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-500);
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 0.5rem 0;
    color: var(--gray-700);
    font-size: 1.25rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .page-header {
        padding: 1.5rem;
    }

    .page-header h1 {
        font-size: 1.75rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.75rem 1rem;
    }

    .product-description {
        max-width: 250px;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .action-section {
        width: 100%;
        justify-content: center;
    }

    .data-table {
        font-size: 0.8rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.5rem 0.75rem;
    }

    .product-name {
        max-width: 150px;
    }

    .product-description {
        max-width: 200px;
        -webkit-line-clamp: 1;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 640px) {
    .page-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .page-header h1 {
        font-size: 1.5rem;
    }

    .data-table-container {
        border-radius: var(--border-radius);
    }

    .data-table th:not(:first-child):not(:last-child),
    .data-table td:not(:first-child):not(:last-child) {
        display: none;
    }

    .product-name {
        max-width: 120px;
    }

    .product-description {
        display: none;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 1.25rem;
    }

    .btn-primary {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.375rem 0.5rem;
    }

    .product-name {
        max-width: 100px;
        font-size: 0.75rem;
    }

    .category-badge {
        font-size: 0.625rem;
        padding: 0.125rem 0.5rem;
    }

    .price-cell {
        font-size: 0.75rem;
    }
}

@media (max-width: 360px) {
    .page-header {
        padding: 0.75rem;
    }

    .page-header h1 {
        font-size: 1.125rem;
    }

    .btn-primary {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }

    .data-table th:first-child,
    .data-table td:first-child {
        padding-left: 0.25rem;
    }

    .data-table th:last-child,
    .data-table td:last-child {
        padding-right: 0.25rem;
    }
}

/* Accessibility and Print Styles */
@media (prefers-reduced-motion: reduce) {
    .btn-primary,
    .btn-action,
    .data-table tbody tr {
        transition: none;
    }

    .btn-primary:hover,
    .btn-action:hover {
        transform: none;
    }
}

@media (prefers-contrast: high) {
    :root {
        --gray-100: #ffffff;
        --gray-200: #e0e0e0;
        --gray-300: #c0c0c0;
    }

    .data-table-container {
        border: 2px solid var(--gray-700);
    }

    .btn-primary {
        border-width: 3px;
    }
}

@media print {
    .page-header {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border: 2px solid #000;
    }

    .btn-primary {
        display: none;
    }

    .data-table-container {
        box-shadow: none;
        border: 1px solid #000;
    }

    .action-buttons {
        display: none;
    }
}

/* Chrome-specific fixes */
@media screen and (-webkit-min-device-pixel-ratio: 0) {
    .data-table {
        min-width: 0;
    }

    .product-name,
    .product-description {
        word-break: break-word;
    }

    .btn-primary,
    .btn-action {
        -webkit-appearance: none;
        border-radius: var(--border-radius);
    }
}

/* Focus states for accessibility */
.btn-primary:focus,
.btn-action:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

.btn-edit:focus {
    outline-color: var(--warning-color);
}

.btn-delete:focus {
    outline-color: var(--danger-color);
}
</style>

<div class="page-header">
    <h1>🛍️ Gestionar Productos</h1>
    <div class="action-section">
        <a href="crear.php" class="btn-primary">
            ➕ Nuevo Producto
        </a>
    </div>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nombre del Producto</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio Venta</th>
                <th>Precio Compra</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td>
                    <div class="product-name" title="<?php echo htmlspecialchars($row['nom_producto']); ?>">
                        <?php echo htmlspecialchars($row['nom_producto']); ?>
                    </div>
                </td>
                <td>
                    <div class="product-description" title="<?php echo htmlspecialchars($row['descripcion'] ?? 'Sin descripción'); ?>">
                        <?php echo htmlspecialchars($row['descripcion'] ?? 'Sin descripción'); ?>
                    </div>
                </td>
                <td>
                    <?php if($row['nom_categoria']): ?>
                        <span class="category-badge"><?php echo htmlspecialchars($row['nom_categoria']); ?></span>
                    <?php else: ?>
                        <span class="category-badge" style="background: var(--gray-200); color: var(--gray-600);">Sin categoría</span>
                    <?php endif; ?>
                </td>
                <td class="price-cell price-sale">
                    $<?php echo number_format($row['precio_venta'], 2); ?>
                </td>
                <td class="price-cell price-cost">
                    $<?php echo number_format($row['precio_compra'], 2); ?>
                </td>
                <td class="actions-cell">
                    <div class="action-buttons">
                        <a href="editar.php?id=<?php echo $row['id_producto']; ?>" class="btn-action btn-edit" title="Editar producto">
                            ✏️ Editar
                        </a>
                        <a href="eliminar.php?id=<?php echo $row['id_producto']; ?>" class="btn-action btn-delete" title="Eliminar producto"
                           onclick="return confirm('¿Está seguro de eliminar este producto?')">
                            🗑️ Eliminar
                        </a>
                    </div>
                </td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <h3>No hay productos registrados</h3>
                        <p>Comience creando su primer producto para gestionar su inventario.</p>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
