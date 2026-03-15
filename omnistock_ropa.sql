gsi
CREATE DATABASE IF NOT EXISTS OmniStock_ropa;
USE OmniStock_ropa;

CREATE TABLE categoria(
id_categoria INT AUTO_INCREMENT PRIMARY KEY,
nom_categoria VARCHAR(100),
desc_categoria VARCHAR(255)
);

CREATE TABLE productos(
id_producto INT AUTO_INCREMENT PRIMARY KEY,
nom_producto VARCHAR(100),
descripcion VARCHAR(255),
precio_compra DECIMAL(10,2),
precio_venta DECIMAL(10,2),
categoria INT,
estado TINYINT DEFAULT 1,
FOREIGN KEY (categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE tallas(
id_talla INT AUTO_INCREMENT PRIMARY KEY,
nombre_talla VARCHAR(10)
);

CREATE TABLE colores(
id_color INT AUTO_INCREMENT PRIMARY KEY,
nombre_color VARCHAR(30)
);

CREATE TABLE inventario(
id_inventario INT AUTO_INCREMENT PRIMARY KEY,
producto INT,
talla INT,
color INT,
stock INT DEFAULT 0,
FOREIGN KEY (producto) REFERENCES productos(id_producto),
FOREIGN KEY (talla) REFERENCES tallas(id_talla),
FOREIGN KEY (color) REFERENCES colores(id_color)
);

CREATE TABLE clientes(
id_cliente INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100),
telefono VARCHAR(20),
correo VARCHAR(100)
);

CREATE TABLE rol(
id_rol INT AUTO_INCREMENT PRIMARY KEY,
nombre_rol VARCHAR(20),
descripcion_rol VARCHAR(100)
);

CREATE TABLE tip_documento(
id_tip_documento INT AUTO_INCREMENT PRIMARY KEY,
tip_documento VARCHAR(20)
);

CREATE TABLE usuarios(
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
nom_usuario VARCHAR(50),
apell_usuario VARCHAR(50),
tipo_documento INT,
N_documento VARCHAR(20),
telefono VARCHAR(20),
correo_usuario VARCHAR(100),
password_usuario VARCHAR(255),
rol INT,
FOREIGN KEY (tipo_documento) REFERENCES tip_documento(id_tip_documento),
FOREIGN KEY (rol) REFERENCES rol(id_rol)
);

CREATE TABLE ventas(
id_venta INT AUTO_INCREMENT PRIMARY KEY,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
cliente INT,
usuario INT,
total DECIMAL(10,2),
FOREIGN KEY (cliente) REFERENCES clientes(id_cliente),
FOREIGN KEY (usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE detalle_venta(
id_detalle INT AUTO_INCREMENT PRIMARY KEY,
venta INT,
inventario INT,
cantidad INT,
precio DECIMAL(10,2),
subtotal DECIMAL(10,2),
FOREIGN KEY (venta) REFERENCES ventas(id_venta),
FOREIGN KEY (inventario) REFERENCES inventario(id_inventario)
);

CREATE TABLE movimientos_inventario(
id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
inventario INT,
tipo_movimiento ENUM('ENTRADA','SALIDA'),
cantidad INT,
fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
observacion VARCHAR(255),
FOREIGN KEY (inventario) REFERENCES inventario(id_inventario)
);

CREATE VIEW vista_inventario AS
SELECT
p.nom_producto,
c.nom_categoria,
t.nombre_talla,
co.nombre_color,
i.stock
FROM inventario i
JOIN productos p ON i.producto=p.id_producto
JOIN categoria c ON p.categoria=c.id_categoria
JOIN tallas t ON i.talla=t.id_talla
JOIN colores co ON i.color=co.id_color;


-- =========================
-- TIPOS DE DOCUMENTO
-- =========================

INSERT INTO tip_documento(tip_documento) VALUES
('Cedula'),
('Pasaporte'),
('Cedula extranjeria'),
('Tarjeta identidad'),
('NIT'),
('Licencia'),
('Documento extranjero'),
('Documento temporal'),
('Permiso especial'),
('Registro civil');


-- =========================
-- ROLES
-- =========================

INSERT INTO rol(nombre_rol,descripcion_rol) VALUES
('Administrador','Control total del sistema'),
('Vendedor','Gestiona ventas'),
('Bodega','Gestiona inventario'),
('Supervisor','Supervisa operaciones'),
('Gerente','Control general'),
('Caja','Registra pagos'),
('Auditor','Consulta reportes'),
('Soporte','Soporte tecnico'),
('Marketing','Gestion promociones'),
('Invitado','Acceso limitado');


-- =========================
-- CATEGORIAS
-- =========================

INSERT INTO categoria(nom_categoria,desc_categoria) VALUES
('Camisetas','Camisetas de diferentes estilos'),
('Jeans','Pantalones tipo jeans'),
('Chaquetas','Chaquetas casuales'),
('Sudaderas','Sudaderas deportivas'),
('Camisas','Camisas formales'),
('Shorts','Pantalones cortos'),
('Vestidos','Vestidos femeninos'),
('Ropa deportiva','Ropa para deporte'),
('Accesorios','Gorras y accesorios'),
('Zapatos','Calzado casual');


-- =========================
-- TALLAS
-- =========================

INSERT INTO tallas(nombre_talla) VALUES
('XS'),
('S'),
('M'),
('L'),
('XL'),
('XXL'),
('36'),
('38'),
('40'),
('42');


-- =========================
-- COLORES
-- =========================

INSERT INTO colores(nombre_color) VALUES
('Negro'),
('Blanco'),
('Azul'),
('Rojo'),
('Gris'),
('Verde'),
('Amarillo'),
('Rosado'),
('Cafe'),
('Beige');


-- =========================
-- PRODUCTOS
-- =========================

INSERT INTO productos(nom_producto,descripcion,precio_compra,precio_venta,categoria) VALUES
('Camiseta Nike','Camiseta deportiva',30000,60000,1),
('Camiseta Adidas','Camiseta deportiva',28000,58000,1),
('Jean Levis','Jean clasico',70000,120000,2),
('Chaqueta Cuero','Chaqueta cuero sintetico',90000,150000,3),
('Sudadera Puma','Sudadera deportiva',65000,110000,4),
('Camisa Formal','Camisa elegante',40000,80000,5),
('Short Deportivo','Short running',25000,50000,6),
('Vestido Casual','Vestido verano',50000,95000,7),
('Gorra Nike','Accesorio deportivo',15000,35000,9),
('Tenis Running','Zapatos deportivos',120000,200000,10);


-- =========================
-- CLIENTES
-- =========================

INSERT INTO clientes(nombre,telefono,correo) VALUES
('Carlos Ramirez','3001112233','carlos@email.com'),
('Maria Lopez','3012223344','maria@email.com'),
('Juan Perez','3023334455','juan@email.com'),
('Laura Torres','3034445566','laura@email.com'),
('Pedro Gomez','3045556677','pedro@email.com'),
('Ana Rodriguez','3056667788','ana@email.com'),
('Luis Martinez','3067778899','luis@email.com'),
('Sofia Vargas','3078889900','sofia@email.com'),
('Diego Castro','3089990011','diego@email.com'),
('Valentina Rojas','3091112233','valentina@email.com');


-- =========================
-- USUARIOS
-- contraseña = 1234567
-- =========================

INSERT INTO usuarios(nom_usuario,apell_usuario,tipo_documento,N_documento,telefono,correo_usuario,password_usuario,rol) VALUES
('Cristofher','Obando',1,'1033772682','3134442014','cobando@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',1),
('Maria','Ramirez',1,'52025546','3143176940','mramirez@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2),
('Juan','Perez',1,'10101010','3000001111','juan@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2),
('Ana','Gomez',1,'20202020','3010001111','ana@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',3),
('Luis','Martinez',1,'30303030','3020001111','luis@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2),
('Sofia','Rojas',1,'40404040','3030001111','sofia@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2),
('Pedro','Castro',1,'50505050','3040001111','pedro@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',4),
('Laura','Torres',1,'60606060','3050001111','laura@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2),
('Diego','Suarez',1,'70707070','3060001111','diego@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',3),
('Valentina','Morales',1,'80808080','3070001111','valentina@omnistock.com','$2y$10$uR6Xz8xP1X9j1pPqT9t3eO2JZr0YvF1QkZJqX5Kk7yZ4LhYQ0P8xG',2);


-- =========================
-- INVENTARIO
-- =========================

INSERT INTO inventario(producto,talla,color,stock) VALUES
(1,3,1,20),
(1,4,1,15),
(2,3,2,18),
(2,4,3,10),
(3,8,3,12),
(4,4,1,8),
(5,3,5,14),
(6,4,2,16),
(7,3,6,9),
(8,3,4,11);


-- =========================
-- VENTAS
-- =========================

INSERT INTO ventas(cliente,usuario,total) VALUES
(1,2,120000),
(2,2,80000),
(3,2,150000),
(4,2,60000),
(5,2,90000),
(6,2,50000),
(7,2,70000),
(8,2,40000),
(9,2,100000),
(10,2,75000);


-- =========================
-- DETALLE VENTAS
-- =========================

INSERT INTO detalle_venta(venta,inventario,cantidad,precio,subtotal) VALUES
(1,1,2,60000,120000),
(2,3,1,58000,58000),
(3,5,1,120000,120000),
(4,7,1,60000,60000),
(5,2,1,60000,60000),
(6,8,1,50000,50000),
(7,6,1,70000,70000),
(8,9,1,40000,40000),
(9,10,1,100000,100000),
(10,4,1,75000,75000);


-- =========================
-- MOVIMIENTOS INVENTARIO
-- =========================

INSERT INTO movimientos_inventario(inventario,tipo_movimiento,cantidad,observacion) VALUES
(1,'ENTRADA',20,'Ingreso inicial'),
(2,'ENTRADA',15,'Ingreso inicial'),
(3,'ENTRADA',18,'Ingreso inicial'),
(4,'ENTRADA',10,'Ingreso inicial'),
(5,'ENTRADA',12,'Ingreso inicial'),
(6,'ENTRADA',8,'Ingreso inicial'),
(7,'ENTRADA',14,'Ingreso inicial'),
(8,'ENTRADA',16,'Ingreso inicial'),
(9,'ENTRADA',9,'Ingreso inicial'),
(10,'ENTRADA',11,'Ingreso inicial');



select * from usuarios;

-- Continuación con productos 21-38 con múltiples talles y colores
INSERT INTO inventario (producto, talla, color, stock) VALUES
-- Leggings (ID 21) - 6 variantes
(21, 1, 1, 70), (21, 2, 1, 80), (21, 3, 1, 90), (21, 4, 1, 85), (21, 5, 1, 60), (21, 6, 1, 45),
-- Sudadera Hoodie (ID 22) - 5 variantes
(22, 2, 1, 35), (22, 3, 1, 40), (22, 4, 1, 45), (22, 5, 1, 35), (22, 6, 1, 25),
-- Tank Top (ID 31) - 5 variantes
(31, 1, 2, 40), (31, 2, 2, 50), (31, 3, 2, 60), (31, 4, 2, 55), (31, 5, 2, 40),
-- Kimono Playa (ID 32) - 5 variantes
(32, 2, 5, 25), (32, 3, 5, 30), (32, 4, 5, 35), (32, 5, 5, 28), (32, 6, 5, 20),
-- Pantalón Lino (ID 33) - 6 variantes
(33, 1, 8, 30), (33, 2, 8, 35), (33, 3, 8, 40), (33, 4, 8, 42), (33, 5, 8, 32), (33, 6, 8, 25),
-- Blazer Oficial (ID 35) - 5 variantes
(35, 2, 1, 22), (35, 3, 1, 25), (35, 4, 1, 28), (35, 5, 1, 22), (35, 6, 1, 18);

-- Verificar total
SELECT COUNT(*) as total_inventario FROM inventario;
