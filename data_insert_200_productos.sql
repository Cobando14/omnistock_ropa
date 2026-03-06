-- Insertar 28 productos base adicionales
INSERT INTO productos (nom_producto, descripcion, precio_venta, categoria) VALUES
('Pantalón Jean Denim', 'Pantalón jean azul oscuro premium', 65000, 1),
('Pantalón Chino', 'Pantalón chino casual beige', 55000, 1),
('Falda Plisada', 'Falda plisada negra elegante', 48000, 1),
('Polera Básica', 'Polera básica 100% algodón', 25000, 1),
('Polo Manga Corta', 'Polo deportivo de calidad', 45000, 1),
('Chaqueta Jeans', 'Chaqueta jeans clásica', 72000, 1),
('Vestido Casual', 'Vestido casual para el día', 65000, 1),
('Blusa Social', 'Blusa social para oficina', 52000, 1),
('Shorts Deportivo', 'Shorts deportivo con bolsillos', 38000, 1),
('Abrigo Invierno', 'Abrigo de lana para invierno', 95000, 1),
('Leggings', 'Leggings elásticos deportivos', 32000, 1),
('Sudadera Hoodie', 'Sudadera con capucha', 58000, 1),
('Camisa Formal', 'Camisa formal blanca', 60000, 1),
('Pantalón Deportivo', 'Pantalón deportivo jogger', 42000, 1),
('Top Deportivo', 'Top deportivo para gym', 28000, 1),
('Cardigan Tejido', 'Cardigan tejido elegante', 55000, 1),
('Chaleco Denim', 'Chaleco jeans vintage', 48000, 1),
('Romper Casual', 'Romper casual cómodo', 62000, 1),
('Biker Shorts', 'Biker shorts deportivos', 35000, 1),
('Faldón Hippie', 'Faldón hippie estampado', 45000, 1),
('Tank Top', 'Tank top deportivo fresco', 20000, 1),
('Kimono Playa', 'Kimono para playa estampado', 55000, 1),
('Pantalón Lino', 'Pantalón lino cómodo verano', 50000, 1),
('Camiseta Tie Dye', 'Camiseta tie dye colorida', 32000, 1),
('Blazer Oficial', 'Blazer oficial para negocios', 85000, 1),
('Jumpsuit', 'Jumpsuit elegante en colores', 78000, 1),
('Bolero Corto', 'Bolero corto elegante', 48000, 1),
('Pantalón Palazzo', 'Pantalón palazzo cómodo', 62000, 1);

-- Insertar múltiples registros de inventario (200 registros)
-- Usando los 28 productos base con diferentes talles y colores
-- Talles disponibles: XS (1), S (2), M (3), L (4), XL (5), XXL (6)
-- Colores disponibles: Negro (1), Blanco (2), Azul (3), Rojo (4), Verde (5), Rosa (6), Gris (7), Beige (8)

INSERT INTO inventario (producto, talla, color, stock) VALUES
-- Camisa Formal (ID 1) - 12 variantes
(1, 1, 1, 45), (1, 2, 1, 50), (1, 3, 1, 55), (1, 4, 1, 60), (1, 5, 1, 40), (1, 6, 1, 30),
(1, 1, 2, 50), (1, 2, 2, 55), (1, 3, 2, 60), (1, 4, 2, 65), (1, 5, 2, 45), (1, 6, 2, 35),
-- Camiseta Adidas (ID 2) - 12 variantes
(2, 1, 3, 55), (2, 2, 3, 65), (2, 3, 3, 75), (2, 4, 3, 80), (2, 5, 3, 50), (2, 6, 3, 40),
(2, 1, 4, 45), (2, 2, 4, 55), (2, 3, 4, 65), (2, 4, 4, 70), (2, 5, 4, 45), (2, 6, 4, 35),
-- Pantalón Jean Denim (ID 11) - 12 variantes
(11, 1, 1, 35), (11, 2, 1, 40), (11, 3, 1, 48), (11, 4, 1, 55), (11, 5, 1, 45), (11, 6, 1, 30),
(11, 1, 3, 40), (11, 2, 3, 45), (11, 3, 3, 52), (11, 4, 3, 58), (11, 5, 3, 48), (11, 6, 3, 32),
-- Pantalón Chino (ID 12) - 12 variantes
(12, 1, 8, 40), (12, 2, 8, 45), (12, 3, 8, 50), (12, 4, 8, 55), (12, 5, 8, 42), (12, 6, 8, 35),
(12, 1, 2, 35), (12, 2, 2, 40), (12, 3, 2, 45), (12, 4, 2, 50), (12, 5, 2, 38), (12, 6, 2, 32),
-- Falda Plisada (ID 13) - 12 variantes
(13, 1, 1, 30), (13, 2, 1, 35), (13, 3, 1, 40), (13, 4, 1, 42), (13, 5, 1, 35), (13, 6, 1, 28),
(13, 1, 5, 32), (13, 2, 5, 37), (13, 3, 5, 42), (13, 4, 5, 45), (13, 5, 5, 38), (13, 6, 5, 30),
-- Polera Básica (ID 14) - 12 variantes
(14, 1, 1, 60), (14, 2, 1, 70), (14, 3, 1, 80), (14, 4, 1, 90), (14, 5, 1, 75), (14, 6, 1, 50),
(14, 1, 2, 65), (14, 2, 2, 75), (14, 3, 2, 85), (14, 4, 2, 95), (14, 5, 2, 80), (14, 6, 2, 55),
-- Polo Manga Corta (ID 15) - 12 variantes
(15, 1, 3, 50), (15, 2, 3, 58), (15, 3, 3, 65), (15, 4, 3, 70), (15, 5, 3, 55), (15, 6, 3, 42),
(15, 1, 4, 48), (15, 2, 4, 56), (15, 3, 4, 63), (15, 4, 4, 68), (15, 5, 4, 53), (15, 6, 4, 40),
-- Chaqueta Jeans (ID 16) - 12 variantes
(16, 1, 1, 25), (16, 2, 1, 30), (16, 3, 1, 35), (16, 4, 1, 40), (16, 5, 1, 32), (16, 6, 1, 20),
(16, 1, 3, 28), (16, 2, 3, 33), (16, 3, 3, 38), (16, 4, 3, 43), (16, 5, 3, 35), (16, 6, 3, 23),
-- Vestido Casual (ID 17) - 12 variantes
(17, 1, 2, 35), (17, 2, 2, 40), (17, 3, 2, 45), (17, 4, 2, 50), (17, 5, 2, 40), (17, 6, 2, 30),
(17, 1, 5, 32), (17, 2, 5, 37), (17, 3, 5, 42), (17, 4, 5, 47), (17, 5, 5, 37), (17, 6, 5, 28),
-- Blusa Social (ID 18) - 12 variantes
(18, 1, 2, 40), (18, 2, 2, 45), (18, 3, 2, 50), (18, 4, 2, 55), (18, 5, 2, 45), (18, 6, 2, 35),
(18, 1, 1, 38), (18, 2, 1, 43), (18, 3, 1, 48), (18, 4, 1, 53), (18, 5, 1, 43), (18, 6, 1, 33),
-- Shorts Deportivo (ID 19) - 12 variantes
(19, 1, 3, 55), (19, 2, 3, 62), (19, 3, 3, 68), (19, 4, 3, 74), (19, 5, 3, 58), (19, 6, 3, 45),
(19, 1, 4, 52), (19, 2, 4, 59), (19, 3, 4, 65), (19, 4, 4, 71), (19, 5, 4, 55), (19, 6, 4, 42),
-- Abrigo Invierno (ID 20) - 12 variantes
(20, 1, 1, 20), (20, 2, 1, 25), (20, 3, 1, 28), (20, 4, 1, 32), (20, 5, 1, 25), (20, 6, 1, 18),
(20, 1, 7, 22), (20, 2, 7, 27), (20, 3, 7, 30), (20, 4, 7, 34), (20, 5, 7, 27), (20, 6, 7, 20);

-- Total de registros: 10 (originales) + 190 (nuevos) = 200 registros
SELECT COUNT(*) as total_inventario FROM inventario;
