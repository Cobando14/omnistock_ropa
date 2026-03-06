-- Agregar más registros de inventario para completar 200 total
-- Actualmente hay 154, necesitamos 46 más

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
