<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM categoria WHERE id_categoria=$id";

if($conn->query($sql)){
    header("Location: index.php?deleted=1");
    exit;
} else {
    header("Location: index.php?error=1");
    exit;
}
?>
