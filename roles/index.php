<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Roles";
include("../includes/header.php");

$sql = "SELECT id_rol, nombre_rol, descripcion_rol FROM rol ORDER BY nombre_rol ASC";
$result = $conn->query($sql);
?>

<div class="action-header">
    <h2>Gestionar Roles</h2>
    <a href="crear.php" class="btn">+ Nuevo Rol</a>
</div>

<table>
    <thead>
        <tr>
            <th>Nombre del Rol</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($result && $result->num_rows > 0){
            while($row=$result->fetch_assoc()){ 
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nombre_rol']); ?></td>
            <td><?php echo htmlspecialchars($row['descripcion_rol'] ?? '-'); ?></td>
            <td>
                <div class="actions">
                    <a href="editar.php?id=<?php echo $row['id_rol']; ?>">✏️ Editar</a>
                    <a href="eliminar.php?id=<?php echo $row['id_rol']; ?>" class="delete" onclick="return confirm('¿Está seguro de eliminar este rol?')">🗑️ Eliminar</a>
                </div>
            </td>
        </tr>
        <?php 
            }
        } else {
            echo '<tr><td colspan="3" style="text-align: center; padding: 2rem; color: #6b7280;">No hay roles registrados</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
