<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - OmniStock' : 'OmniStock'; ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <header>
        <h1>OmniStock</h1>
        <p>Sistema de Gestión de Inventario</p>
    </header>

    <nav>
        <ul>
            <li><a href="../dashboard/index.php">Dashboard</a></li>
            <li><a href="../usuarios/index.php">Usuarios</a></li>
            <li><a href="../productos/index.php">Productos</a></li>
            <li><a href="../categorias/index.php">Categorías</a></li>
            <li><a href="../roles/index.php">Roles</a></li>
            <li><a href="../ventas/index.php">Ventas</a></li>
            <li><a href="../inventario/index.php">Inventario</a></li>
            <li><a href="../logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <div class="container">
