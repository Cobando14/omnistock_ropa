<?php
session_start();

// Si ya existe sesión, ir al dashboard
if(isset($_SESSION['usuario_id'])){
    header('Location: dashboard/index.php');
    exit;
}

// Si no existe sesión, ir al login
header('Location: login/login.php');
exit;
?>
