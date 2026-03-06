<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Usuarios";
include("../includes/header.php");

$sql = "SELECT u.id_usuario,u.nom_usuario,u.apell_usuario,td.tip_documento,u.N_documento, r.nombre_rol,u.correo_usuario,u.telefono
FROM usuarios u
INNER JOIN rol r ON u.rol = r.id_rol
INNER JOIN tip_documento td ON u.tipo_documento=td.id_tip_documento
ORDER BY u.id_usuario DESC;";

$result = $conn->query($sql);
?>

<div class="action-header">
    <h2>Gestionar Usuarios</h2>
    <a href="crear.php" class="btn">+ Nuevo Usuario</a>
</div>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Tipo de Documento</th>
            <th>Documento</th>
            <th>Rol</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nom_usuario']); ?></td>
            <td><?php echo htmlspecialchars($row['apell_usuario']); ?></td>
            <td><?php echo htmlspecialchars($row['tip_documento']); ?></td>
            <td><?php echo htmlspecialchars($row['N_documento']); ?></td>
            <td><?php echo htmlspecialchars($row['nombre_rol']); ?></td>
            <td><?php echo htmlspecialchars($row['correo_usuario']); ?></td>
            <td><?php echo htmlspecialchars($row['telefono'] ?? '-'); ?></td>
            <td>
                <div class="actions">
                    <a href="editar.php?id=<?php echo $row['id_usuario']; ?>">✏️ Editar</a>
                    <a href="eliminar.php?id=<?php echo $row['id_usuario']; ?>" class="delete" onclick="return confirm('¿Está seguro de eliminar este usuario?')">🗑️ Eliminar</a>
                </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>