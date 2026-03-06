<?php
include("../config/conexion.php");
include("../includes/auth.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$id_venta = intval($_GET['id']);

// Verificar que la venta existe
$check_sql = "SELECT id_venta FROM ventas WHERE id_venta = $id_venta";
$check_result = $conn->query($check_sql);

if($check_result->num_rows == 0){
    header("Location: index.php?error=1");
    exit;
}

// Usar transacción para eliminar venta y detalles
$conn->begin_transaction();

try {
    // Eliminar detalles de venta primero
    $sql_detalles = "DELETE FROM detalle_venta WHERE venta = $id_venta";
    if(!$conn->query($sql_detalles)){
        throw new Exception("Error al eliminar detalles: " . $conn->error);
    }
    
    // Luego eliminar venta
    $sql_venta = "DELETE FROM ventas WHERE id_venta = $id_venta";
    if(!$conn->query($sql_venta)){
        throw new Exception("Error al eliminar venta: " . $conn->error);
    }
    
    $conn->commit();
    header("Location: index.php?deleted=1");
    exit;
} catch(Exception $e){
    $conn->rollback();
    header("Location: index.php?error=1");
    exit;
}
?>
