<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OmniStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body style="display: flex; flex-direction: column; justify-content: center; min-height: 100vh;">
    <header style="text-align: center; margin-bottom: 3rem;">
        <h1>OmniStock</h1>
        <p>Sistema de Gestión de Inventario</p>
    </header>

    <div class="login-box">
        <h2>Iniciar Sesión</h2>

        <form action="validar_login.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
            </div>

            <button type="submit" style="width: 100%;">Ingresar</button>
        </form>
    </div>

</body>
</html>