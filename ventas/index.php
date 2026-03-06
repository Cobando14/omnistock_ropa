<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Ventas";
include("../includes/header.php");

$sql = "SELECT v.id_venta, v.fecha, c.nombre, u.nom_usuario, v.total
FROM ventas v
LEFT JOIN clientes c ON v.cliente = c.id_cliente
LEFT JOIN usuarios u ON v.usuario = u.id_usuario
ORDER BY v.fecha DESC";

$result = $conn->query($sql);
?>

<style>
/* Shared design variables and utilities */
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
    border-bottom: 1rem solid var(--gray-200);
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

.data-table tbody tr:hover {
    background: var(--gray-50);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.sale-id {
    font-weight: 600;
    color: var(--primary-dark);
}

.sale-date {
    color: var(--gray-600);
    white-space: nowrap;
}

.sale-client,
.sale-user {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.price-cell {
    font-weight: 600;
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

.btn-view {
    background: var(--success-color);
    color: white;
}

.btn-view:hover {
    background: #059669;
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

/* Responsive breakpoints (same as products module) */
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
    .sale-client,
    .sale-user {
        max-width: 140px;
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
    .sale-id {
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
}

/* Accessibility, print, Chrome fixes same as before */
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

@media screen and (-webkit-min-device-pixel-ratio: 0) {
    .data-table {
        min-width: 0;
    }
    .btn-primary,
    .btn-action {
        -webkit-appearance: none;
        border-radius: var(--border-radius);
    }
}

.btn-primary:focus,
.btn-action:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

.btn-view:focus {
    outline-color: var(--success-color);
}

.btn-delete:focus {
    outline-color: var(--danger-color);
}
</style>

<div class="page-header">
    <h1>💰 Gestionar Ventas</h1>
    <div class="action-section">
        <a href="crear.php" class="btn-primary">
            ➕ Nueva Venta
        </a>
    </div>
</div>

<div class="data-table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($result && $result->num_rows > 0){
                while($row=$result->fetch_assoc()){
            ?>
            <tr>
                <td class="sale-id">#<?php echo $row['id_venta']; ?></td>
                <td class="sale-date"><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                <td class="sale-client" title="<?php echo htmlspecialchars($row['nombre'] ?? 'Cliente no registrado'); ?>"><?php echo htmlspecialchars($row['nombre'] ?? 'Cliente no registrado'); ?></td>
                <td class="sale-user" title="<?php echo htmlspecialchars($row['nom_usuario'] ?? '-'); ?>"><?php echo htmlspecialchars($row['nom_usuario'] ?? '-'); ?></td>
                <td class="price-cell">$<?php echo number_format($row['total'], 2); ?></td>
                <td class="actions-cell">
                    <div class="action-buttons">
                        <a href="detalle.php?id=<?php echo $row['id_venta']; ?>" class="btn-action btn-view" title="Ver venta">
                            👁️ Ver
                        </a>
                        <a href="eliminar.php?id=<?php echo $row['id_venta']; ?>" class="btn-action btn-delete" title="Eliminar venta" onclick="return confirm('¿Está seguro de eliminar esta venta?')">
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
                        <div class="empty-state-icon">💸</div>
                        <h3>No hay ventas registradas</h3>
                        <p>Realice una venta para observarla aquí.</p>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
