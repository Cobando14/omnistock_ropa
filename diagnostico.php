<?php
/**
 * Script de Diagnóstico - OmniStock
 * Verifica el estado de la conexión a la BD y la estructura de las tablas
 */

// Configuración
$host = "localhost";
$user = "root";
$password = "obando123";
$db = "omnistock_ropa";

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><meta charset='UTF-8'><title>Diagnóstico OmniStock</title>";
echo "<style>body { font-family: Arial; margin: 20px; } ";
echo ".success { color: green; padding: 10px; background: #e8f5e9; margin: 10px 0; border-radius: 4px; } ";
echo ".error { color: red; padding: 10px; background: #ffebee; margin: 10px 0; border-radius: 4px; } ";
echo ".warning { color: orange; padding: 10px; background: #fff3e0; margin: 10px 0; border-radius: 4px; } ";
echo ".info { color: blue; padding: 10px; background: #e3f2fd; margin: 10px 0; border-radius: 4px; } ";
echo "table { border-collapse: collapse; width: 100%; margin: 10px 0; } ";
echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } ";
echo "th { background: #f5f5f5; } ";
echo "</style></head><body>";

echo "<h1>🔍 Diagnóstico de OmniStock</h1>";
echo "<p>Fecha: " . date('Y-m-d H:i:s') . "</p>";

// 1. Intentar conexión
echo "<h2>1. Prueba de Conexión a la Base de Datos</h2>";

$conn = new mysqli($host, $user, $password, $db);

if($conn->connect_error){
    echo "<div class='error'><strong>❌ Error de Conexión:</strong> " . htmlspecialchars($conn->connect_error) . "</div>";
    echo "<div class='info'><strong>Información:</strong>";
    echo "<ul>";
    echo "<li><strong>Host:</strong> $host</li>";
    echo "<li><strong>Usuario:</strong> $user</li>";
    echo "<li><strong>Base de Datos:</strong> $db</li>";
    echo "<li><strong>Puerto:</strong> Por defecto (3306)</li>";
    echo "</ul>";
    echo "<p><strong>Soluciones:</strong></p>";
    echo "<ul>";
    echo "<li>Verifica que MySQL esté corriendo en XAMPP</li>";
    echo "<li>Verifica que el puerto 3306 esté disponible</li>";
    echo "<li>Verifica que la contraseña sea correcta</li>";
    echo "<li>Intenta reinicar MySQL en XAMPP Control Panel</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div class='success'><strong>✅ Conexión Exitosa</strong></div>";
    
    // 2. Verificar estructura de tablas
    echo "<h2>2. Verificación de Tablas</h2>";
    
    $tablas_esperadas = array(
        'categoria', 'productos', 'tallas', 'colores', 'inventario', 
        'clientes', 'rol', 'tip_documento', 'usuarios', 'ventas', 'detalle_venta'
    );
    
    echo "<table>";
    echo "<thead><tr><th>Tabla</th><th>Estado</th><th>Registros</th></tr></thead><tbody>";
    
    foreach($tablas_esperadas as $tabla){
        $sql = "SELECT COUNT(*) as count FROM $tabla";
        $result = $conn->query($sql);
        
        if($result){
            $row = $result->fetch_assoc();
            $count = $row['count'];
            echo "<tr><td>$tabla</td><td><span style='color: green;'>✅ Existe</span></td><td>$count</td></tr>";
        } else {
            echo "<tr><td>$tabla</td><td><span style='color: red;'>❌ No existe</span></td><td>-</td></tr>";
        }
    }
    
    echo "</tbody></table>";
    
    // 3. Verificar estructura de columnas críticas
    echo "<h2>3. Verificación de Columnas Críticas</h2>";
    
    $columnas_criticas = array(
        'usuarios' => array('id_usuario', 'nom_usuario', 'apell_usuario', 'correo_usuario', 'password_usuario', 'rol'),
        'productos' => array('id_producto', 'nom_producto', 'precio_venta', 'precio_compra', 'categoria'),
        'inventario' => array('id_inventario', 'producto', 'stock'),
        'ventas' => array('id_venta', 'fecha', 'cliente', 'usuario', 'total'),
        'clientes' => array('id_cliente', 'nombre', 'correo', 'telefono')
    );
    
    foreach($columnas_criticas as $tabla => $columnas){
        echo "<h3>Tabla: $tabla</h3>";
        echo "<table>";
        echo "<thead><tr><th>Columna</th><th>Estado</th></tr></thead><tbody>";
        
        $sql = "DESCRIBE $tabla";
        $result = $conn->query($sql);
        $cols_bd = array();
        
        while($row = $result->fetch_assoc()){
            $cols_bd[] = $row['Field'];
        }
        
        foreach($columnas as $col){
            if(in_array($col, $cols_bd)){
                echo "<tr><td>$col</td><td><span style='color: green;'>✅ Existe</span></td></tr>";
            } else {
                echo "<tr><td>$col</td><td><span style='color: red;'>❌ No existe</span></td></tr>";
            }
        }
        
        echo "</tbody></table>";
    }
    
    // 4. Verificar datos de prueba
    echo "<h2>4. Verificación de Datos de Prueba</h2>";
    
    $sql = "SELECT COUNT(*) as count FROM rol";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<p><strong>Roles:</strong> " . $row['count'] . " registros</p>";
    
    $sql = "SELECT COUNT(*) as count FROM usuarios";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<p><strong>Usuarios:</strong> " . $row['count'] . " registros</p>";
    
    $sql = "SELECT COUNT(*) as count FROM productos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo "<p><strong>Productos:</strong> " . $row['count'] . " registros</p>";
    
    // 5. Próximos pasos
    echo "<h2>5. Próximos Pasos</h2>";
    
    if($row['count'] == 0){
        echo "<div class='warning'><strong>⚠️ No hay productos registrados</strong>";
        echo "<p>Ejecuta los scripts SQL para cargar los datos:</p>";
        echo "<ul>";
        echo "<li>data_insert_46_adicionales.sql</li>";
        echo "<li>data_insert_200_productos.sql</li>";
        echo "</ul></div>";
    } else {
        echo "<div class='success'><strong>✅ La BD está lista para usar</strong></div>";
    }
}

$conn->close();

echo "<hr>";
echo "<p><small>Este diagnóstico fue generado para verificar la configuración de OmniStock</small></p>";
echo "</body></html>";
?>
