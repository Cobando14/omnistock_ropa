<?php
include("../includes/auth.php");
$page_title = "Dashboard";
include("../includes/header.php");
?>

<div class="page-header">
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h2>
    <p style="color: #6b7280; margin-top: 0.5rem;">Panel de Control</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
    <a href="../ventas/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">💰</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Ventas</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Registrar y gestionar ventas</p>
    </a>

    <a href="../inventario/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">📊</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Inventario</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Control de 200 productos en stock</p>
    </a>

    <a href="../productos/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">📦</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Productos</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Gestionar inventario de productos</p>
    </a>

    <a href="../categorias/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">🏷️</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Categorías</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Administrar categorías de productos</p>
    </a>

    <a href="../usuarios/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">👥</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Usuarios</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Gestionar usuarios del sistema</p>
    </a>

    <a href="../roles/index.php" style="padding: 2rem; background: white; border-radius: 8px; text-decoration: none; color: inherit; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: all 0.2s; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
        <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">🔐</div>
        <h3 style="color: #111827; margin-bottom: 0.5rem;">Roles</h3>
        <p style="color: #6b7280; font-size: 0.9rem; text-align: center;">Configurar permisos y roles</p>
    </a>
</div>

<?php include("../includes/footer.php"); ?>