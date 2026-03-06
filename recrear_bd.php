<?php
/**
 * Script de Recreación de Base de Datos - OmniStock
 * Elimina y recrea completamente la BD omnistock_ropa
 * 
 * IMPORTANTE: Ejecutar SOLO una vez para limpiar y reconfigurar
 */

// Conectar SIN especificar BD
$host = "localhost";
$user = "root";
$password = "obando123";
$port = 3306;

$conn = new mysqli($host, $user, $password, "", $port);

if($conn->connect_error){
    die("<h1>Error de Conexión</h1><p>No se pudo conectar a MySQL: " . htmlspecialchars($conn->connect_error) . "</p><p>Verifica que MySQL esté ejecutándose en XAMPP.</p>");
}

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Recrear BD - OmniStock</title>";
echo "<style>body { font-family: Arial; margin: 30px; line-height: 1.6; }";
echo ".success { color: green; padding: 15px; background: #e8f5e9; border-left: 4px solid green; margin: 10px 0; }";
echo ".error { color: red; padding: 15px; background: #ffebee; border-left: 4px solid red; margin: 10px 0; }";
echo ".warning { color: orange; padding: 15px; background: #fff3e0; border-left: 4px solid orange; margin: 10px 0; }";
echo ".info { color: #1976d2; padding: 15px; background: #e3f2fd; border-left: 4px solid #1976d2; margin: 10px 0; }";
echo "code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }";
echo "</style></head><body>";

echo "<h1>🔄 Recreación de Base de Datos - OmniStock</h1>";
echo "<p>Iniciando proceso de limpieza y reconstrucción...</p>";

// Paso 1: Eliminar BD existente
echo "<h2>Paso 1: Eliminando BD anterior (si existe)</h2>";

$sql = "DROP DATABASE IF EXISTS omnistock_ropa";
if($conn->query($sql)){
    echo "<div class='success'>✅ Base de datos anterior eliminada correctamente</div>";
} else {
    echo "<div class='error'>❌ Error al eliminar BD: " . $conn->error . "</div>";
}

// Paso 2: Crear nueva BD
echo "<h2>Paso 2: Creando nueva base de datos</h2>";

$sql = "CREATE DATABASE IF NOT EXISTS omnistock_ropa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if($conn->query($sql)){
    echo "<div class='success'>✅ Base de datos creada correctamente</div>";
} else {
    echo "<div class='error'>❌ Error al crear BD: " . $conn->error . "</div>";
    die("No se puede continuar sin la BD.");
}

// Paso 3: Seleccionar BD
$conn->select_db("omnistock_ropa");

// Paso 4: Crear tablas
echo "<h2>Paso 3: Creando tablas</h2>";

$tablas = array(
    "categoria" => "CREATE TABLE categoria(id_categoria INT AUTO_INCREMENT PRIMARY KEY, nom_categoria VARCHAR(100), desc_categoria VARCHAR(255))",
    "productos" => "CREATE TABLE productos(id_producto INT AUTO_INCREMENT PRIMARY KEY, nom_producto VARCHAR(100), descripcion VARCHAR(255), precio_compra DECIMAL(10,2), precio_venta DECIMAL(10,2), categoria INT, estado TINYINT DEFAULT 1, FOREIGN KEY (categoria) REFERENCES categoria(id_categoria))",
    "tallas" => "CREATE TABLE tallas(id_talla INT AUTO_INCREMENT PRIMARY KEY, nombre_talla VARCHAR(10))",
    "colores" => "CREATE TABLE colores(id_color INT AUTO_INCREMENT PRIMARY KEY, nombre_color VARCHAR(30))",
    "inventario" => "CREATE TABLE inventario(id_inventario INT AUTO_INCREMENT PRIMARY KEY, producto INT, talla INT, color INT, stock INT DEFAULT 0, FOREIGN KEY (producto) REFERENCES productos(id_producto), FOREIGN KEY (talla) REFERENCES tallas(id_talla), FOREIGN KEY (color) REFERENCES colores(id_color))",
    "clientes" => "CREATE TABLE clientes(id_cliente INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(100), telefono VARCHAR(20), correo VARCHAR(100))",
    "rol" => "CREATE TABLE rol(id_rol INT AUTO_INCREMENT PRIMARY KEY, nombre_rol VARCHAR(20), descripcion_rol VARCHAR(100))",
    "tip_documento" => "CREATE TABLE tip_documento(id_tip_documento INT AUTO_INCREMENT PRIMARY KEY, tip_documento VARCHAR(20))",
    "usuarios" => "CREATE TABLE usuarios(id_usuario INT AUTO_INCREMENT PRIMARY KEY, nom_usuario VARCHAR(50), apell_usuario VARCHAR(50), tipo_documento INT, N_documento VARCHAR(20), telefono VARCHAR(20), correo_usuario VARCHAR(100), password_usuario VARCHAR(255), rol INT, FOREIGN KEY (tipo_documento) REFERENCES tip_documento(id_tip_documento), FOREIGN KEY (rol) REFERENCES rol(id_rol))",
    "ventas" => "CREATE TABLE ventas(id_venta INT AUTO_INCREMENT PRIMARY KEY, fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP, cliente INT, usuario INT, total DECIMAL(10,2), FOREIGN KEY (cliente) REFERENCES clientes(id_cliente), FOREIGN KEY (usuario) REFERENCES usuarios(id_usuario))",
    "detalle_venta" => "CREATE TABLE detalle_venta(id_detalle INT AUTO_INCREMENT PRIMARY KEY, venta INT, inventario INT, cantidad INT, precio DECIMAL(10,2), subtotal DECIMAL(10,2), FOREIGN KEY (venta) REFERENCES ventas(id_venta), FOREIGN KEY (inventario) REFERENCES inventario(id_inventario))",
    "movimientos_inventario" => "CREATE TABLE movimientos_inventario(id_movimiento INT AUTO_INCREMENT PRIMARY KEY, inventario INT, tipo_movimiento ENUM('ENTRADA','SALIDA'), cantidad INT, fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP, observacion VARCHAR(255), FOREIGN KEY (inventario) REFERENCES inventario(id_inventario))"
);

$tablas_ok = 0;
foreach($tablas as $nombre => $sql){
    if($conn->query($sql)){
        echo "<div class='success'>✅ Tabla <code>$nombre</code> creada</div>";
        $tablas_ok++;
    } else {
        echo "<div class='error'>❌ Error en tabla <code>$nombre</code>: " . $conn->error . "</div>";
    }
}

// Paso 5: Crear vista
echo "<h2>Paso 4: Creando vista</h2>";

$sql_vista = "CREATE VIEW vista_inventario AS SELECT p.nom_producto, c.nom_categoria, t.nombre_talla, co.nombre_color, i.stock FROM inventario i JOIN productos p ON i.producto=p.id_producto JOIN categoria c ON p.categoria=c.id_categoria JOIN tallas t ON i.talla=t.id_talla JOIN colores co ON i.color=co.id_color";
if($conn->query($sql_vista)){
    echo "<div class='success'>✅ Vista <code>vista_inventario</code> creada</div>";
} else {
    echo "<div class='warning'>⚠️ Aviso al crear vista (puede ser normal): " . $conn->error . "</div>";
}

// Paso 6: Insertar datos iniciales
echo "<h2>Paso 5: Insertando datos iniciales</h2>";

// Tipos de documento
$tipos_doc = array(
    "INSERT INTO tip_documento(tip_documento) VALUES ('Cedula')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Pasaporte')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Cedula extranjeria')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Tarjeta identidad')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('NIT')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Licencia')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Documento extranjero')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Documento temporal')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Permiso especial')",
    "INSERT INTO tip_documento(tip_documento) VALUES ('Registro civil')"
);

foreach($tipos_doc as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Tipos de documento insertados (10)</div>";

// Roles
$roles = array(
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Administrador','Control total del sistema')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Vendedor','Gestiona ventas')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Bodega','Gestiona inventario')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Supervisor','Supervisa operaciones')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Gerente','Control general')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Caja','Registra pagos')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Auditor','Consulta reportes')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Soporte','Soporte tecnico')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Marketing','Gestion promociones')",
    "INSERT INTO rol(nombre_rol,descripcion_rol) VALUES ('Invitado','Acceso limitado')"
);

foreach($roles as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Roles insertados (10)</div>";

// Categorías
$categorias = array(
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Camisetas','Camisetas de diferentes estilos')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Jeans','Pantalones tipo jeans')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Chaquetas','Chaquetas casuales')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Sudaderas','Sudaderas deportivas')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Camisas','Camisas formales')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Shorts','Pantalones cortos')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Vestidos','Vestidos femeninos')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Ropa deportiva','Ropa para deporte')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Accesorios','Gorras y accesorios')",
    "INSERT INTO categoria(nom_categoria,desc_categoria) VALUES ('Zapatos','Calzado casual')"
);

foreach($categorias as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Categorías insertadas (10)</div>";

// Tallas
$tallas = array(
    "INSERT INTO tallas(nombre_talla) VALUES ('XS')",
    "INSERT INTO tallas(nombre_talla) VALUES ('S')",
    "INSERT INTO tallas(nombre_talla) VALUES ('M')",
    "INSERT INTO tallas(nombre_talla) VALUES ('L')",
    "INSERT INTO tallas(nombre_talla) VALUES ('XL')",
    "INSERT INTO tallas(nombre_talla) VALUES ('XXL')",
    "INSERT INTO tallas(nombre_talla) VALUES ('36')",
    "INSERT INTO tallas(nombre_talla) VALUES ('38')",
    "INSERT INTO tallas(nombre_talla) VALUES ('40')",
    "INSERT INTO tallas(nombre_talla) VALUES ('42')"
);

foreach($tallas as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Tallas insertadas (10)</div>";

// Colores
$colores = array(
    "INSERT INTO colores(nombre_color) VALUES ('Negro')",
    "INSERT INTO colores(nombre_color) VALUES ('Blanco')",
    "INSERT INTO colores(nombre_color) VALUES ('Azul')",
    "INSERT INTO colores(nombre_color) VALUES ('Rojo')",
    "INSERT INTO colores(nombre_color) VALUES ('Gris')",
    "INSERT INTO colores(nombre_color) VALUES ('Verde')",
    "INSERT INTO colores(nombre_color) VALUES ('Amarillo')",
    "INSERT INTO colores(nombre_color) VALUES ('Rosado')",
    "INSERT INTO colores(nombre_color) VALUES ('Cafe')",
    "INSERT INTO colores(nombre_color) VALUES ('Beige')"
);

foreach($colores as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Colores insertados (10)</div>";

// Productos
$productos = array(
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Camiseta Nike','Camiseta deportiva',30000,60000,1)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Camiseta Adidas','Camiseta deportiva',28000,58000,1)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Jean Levis','Jean clasico',70000,120000,2)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Chaqueta Cuero','Chaqueta cuero sintetico',90000,150000,3)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Sudadera Puma','Sudadera deportiva',65000,110000,4)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Camisa Formal','Camisa elegante',40000,80000,5)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Short Deportivo','Short running',25000,50000,6)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Vestido Casual','Vestido verano',50000,95000,7)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Gorra Nike','Accesorio deportivo',15000,35000,9)",
    "INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES ('Tenis Running','Zapatos deportivos',120000,200000,10)"
);

foreach($productos as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Productos insertados (10)</div>";

// Clientes
$clientes = array(
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Carlos Ramirez','3001112233','carlos@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Maria Lopez','3012223344','maria@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Juan Perez','3023334455','juan@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Laura Torres','3034445566','laura@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Pedro Gomez','3045556677','pedro@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Ana Rodriguez','3056667788','ana@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Luis Martinez','3067778899','luis@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Sofia Vargas','3078889900','sofia@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Diego Castro','3089990011','diego@email.com')",
    "INSERT INTO clientes(nombre,telefono,correo) VALUES ('Valentina Rojas','3091112233','valentina@email.com')"
);

foreach($clientes as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Clientes insertados (10)</div>";

// Usuarios (contraseña: 1234567)
$usuarios = array(
    "INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES ('Cristofher','Obando',1,'1033772682','3134442014','cobando@omnistock.com','\$2y\$10\$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',1)",
    "INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES ('Maria','Ramirez',1,'52025546','3143176940','mramirez@omnistock.com','\$2y\$10\$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2)",
    "INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES ('Juan','Perez',1,'10101010','3000001111','juan@omnistock.com','\$2y\$10\$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2)",
    "INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES ('Ana','Gomez',1,'20202020','3010001111','ana@omnistock.com','\$2y\$10\$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',3)",
    "INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES ('Luis','Martinez',1,'30303030','3020001111','luis@omnistock.com','\$2y\$10\$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2)"
);

foreach($usuarios as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Usuarios insertados (5)</div>";

// Inventario
$inventario = array(
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (1,3,1,20)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (1,4,1,15)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (2,3,2,18)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (2,4,3,10)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (3,8,3,12)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (4,4,1,8)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (5,3,5,14)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (6,4,2,16)",
    "INSERT INTO inventario(producto,talla,color,stock) VALUES (7,3,6,9)"
);

foreach($inventario as $sql){
    $conn->query($sql);
}
echo "<div class='success'>✅ Items de inventario insertados (9)</div>";

// Paso 6: Resumen
echo "<h2>✅ Proceso Completado</h2>";

echo "<div class='success'>";
echo "<h3>Base de Datos Reconstruida Exitosamente</h3>";
echo "<p><strong>Base de datos:</strong> <code>omnistock_ropa</code></p>";
echo "<p><strong>Tablas creadas:</strong> 11</p>";
echo "<p><strong>Datos insertados:</strong></p>";
echo "<ul>";
echo "<li>Tipos de Documento: 10</li>";
echo "<li>Roles: 10</li>";
echo "<li>Categorías: 10</li>";
echo "<li>Tallas: 10</li>";
echo "<li>Colores: 10</li>";
echo "<li>Productos: 10</li>";
echo "<li>Clientes: 10</li>";
echo "<li>Usuarios: 5 (contraseña: 1234567)</li>";
echo "<li>Items de Inventario: 9</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔑 Credenciales para Login</h2>";
echo "<div class='info'>";
echo "<p><strong>Usuario Administrador:</strong></p>";
echo "<ul>";
echo "<li><strong>Email:</strong> cobando@omnistock.com</li>";
echo "<li><strong>Contraseña:</strong> 1234567</li>";
echo "</ul>";
echo "<p><strong>Usuario Vendedor:</strong></p>";
echo "<ul>";
echo "<li><strong>Email:</strong> mramirez@omnistock.com</li>";
echo "<li><strong>Contraseña:</strong> 1234567</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🚀 Próximos Pasos</h2>";
echo "<div class='info'>";
echo "<p><strong>1. Ahora puedes:</strong></p>";
echo "<ul>";
echo "<li>❌ Elimina este archivo (<code>recrear_bd.php</code>) para evitar acceso accidental</li>";
echo "<li>✅ Ve a <a href='../login/login.php' target='_blank'>Login</a></li>";
echo "<li>✅ Usa las credenciales anteriores</li>";
echo "</ul>";
echo "</div>";

$conn->close();

echo "<hr>";
echo "<p><small>Script de recreación completado el " . date('Y-m-d H:i:s') . "</small></p>";
echo "</body></html>";
?>
