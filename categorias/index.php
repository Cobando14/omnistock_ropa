<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Categorías";
include("../includes/header.php");

$sql = "SELECT id_categoria, nom_categoria, desc_categoria FROM categoria ORDER BY nom_categoria ASC";
$result = $conn->query($sql);
?>

<div class="container">
    <div class="page-header">
        <h2>📂 Gestionar Categorías</h2>
        <p style="color: #6b7280;">Organiza y administra las categorías de productos</p>
        <?php if($result && $result->num_rows > 0): ?>
        <p style="color: #374151; font-weight: 500; margin-top: 0.5rem;">
            📊 <?php echo $result->num_rows; ?> categoría(s) registrada(s)
        </p>
        <?php endif; ?>
    </div>

    <div class="action-section">
        <a href="crear.php" class="btn-primary">
            <span>+</span>
            Nueva Categoría
        </a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>🏷️ Nombre</th>
                    <th>📝 Descripción</th>
                    <th>⚙️ Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($result && $result->num_rows > 0){
                    while($row=$result->fetch_assoc()){
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['nom_categoria']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['desc_categoria'] ?? '-'); ?></td>
                    <td>
                        <div class="actions">
                            <a href="editar.php?id=<?php echo $row['id_categoria']; ?>" class="btn-edit">
                                <span>✏️</span>
                                Editar
                            </a>
                            <a href="eliminar.php?id=<?php echo $row['id_categoria']; ?>" class="btn-delete"
                               onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                <span>🗑️</span>
                                Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="3" style="text-align: center; padding: 3rem; color: #6b7280; font-style: italic;">No hay categorías registradas<br><small>Crea tu primera categoría para comenzar</small></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        width: 100%;
        box-sizing: border-box;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .page-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .page-header p {
        font-size: 1.1rem;
        color: #64748b;
    }

    .action-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }

    .btn-primary {
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table th {
        padding: 1.25rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        cursor: pointer;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .btn-edit:hover {
        background: #bfdbfe;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .btn-delete:hover {
        background: #fee2e2;
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .page-header h2 {
            font-size: 2rem;
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-section {
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .data-table {
            font-size: 0.85rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem 0.5rem;
        }

        .actions {
            flex-direction: column;
            gap: 0.375rem;
        }

        .btn-edit, .btn-delete {
            justify-content: center;
            padding: 0.625rem 0.875rem;
        }
    }

    @media (max-width: 480px) {
        .page-header h2 {
            font-size: 1.75rem;
        }

        .data-table th:nth-child(2) {
            display: none;
        }

        .data-table td:nth-child(2) {
            display: none;
        }

        .data-table th,
        .data-table td {
            padding: 0.5rem 0.375rem;
        }
    }

    /* Print styles */
    @media print {
        .action-section {
            display: none;
        }

        .data-table {
            box-shadow: none;
            border: 1px solid #000;
        }

        .btn-edit, .btn-delete {
            display: none;
        }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
        .table-container {
            border: 2px solid #000;
        }

        .btn-primary, .btn-edit, .btn-delete {
            border: 2px solid #000;
        }
    }

    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        .btn-primary, .btn-edit, .btn-delete {
            transition: none;
        }

        .btn-primary:hover, .btn-edit:hover, .btn-delete:hover {
            transform: none;
        }

        .data-table tbody tr:hover {
            background-color: transparent;
        }
    }
</style>

<?php include("../includes/footer.php"); ?>
