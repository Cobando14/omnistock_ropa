<?php
include("../config/conexion.php");
include("../includes/auth.php");
$page_title = "Crear Venta";
include("../includes/header.php");

// Obtener inventario disponible
$inventario_result = $conn->query("SELECT i.id_inventario, p.nom_producto, p.precio_venta, i.stock, 
t.nombre_talla, c.nombre_color FROM inventario i
JOIN productos p ON i.producto = p.id_producto
LEFT JOIN tallas t ON i.talla = t.id_talla
LEFT JOIN colores c ON i.color = c.id_color
WHERE i.stock > 0
ORDER BY p.nom_producto ASC");

// Obtener clientes
$clientes_result = $conn->query("SELECT id_cliente, nombre FROM clientes ORDER BY nombre ASC");

// Procesar guardado de venta
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cliente = intval($_POST['cliente'] ?? 0);
    $usuario = intval($_SESSION['id_usuario'] ?? 0);
    $productos = $_POST['producto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $descuentos = $_POST['descuento'] ?? [];
    
    if(empty($productos) || count($productos) == 0 || empty(array_filter($cantidades))){
        $error = "Debe agregar al menos un producto a la venta";
    } else {
        $conn->begin_transaction();
        
        try {
            $total_venta = 0;
            $subtotal_venta = 0;
            $descuento_total = 0;
            
            // Calcular totales
            foreach($productos as $idx => $id_inv){
                $id_inv = intval($id_inv);
                $cantidad = floatval($cantidades[$idx] ?? 0);
                $descuento = floatval($descuentos[$idx] ?? 0);
                
                if($cantidad <= 0 || $id_inv <= 0) continue;
                
                // Obtener precio desde inventario
                $inv_sql = "SELECT p.precio_venta, i.stock FROM inventario i 
                JOIN productos p ON i.producto = p.id_producto 
                WHERE i.id_inventario = $id_inv";
                $inv_result = $conn->query($inv_sql);
                
                if($inv_result->num_rows == 0) continue;
                
                $inv_data = $inv_result->fetch_assoc();
                
                $precio = floatval($inv_data['precio_venta']);
                $stock_disponible = intval($inv_data['stock']);
                
                if($cantidad > $stock_disponible){
                    throw new Exception("Stock insuficiente para el producto (disponible: $stock_disponible)");
                }
                
                $subtotal_linea = $cantidad * $precio;
                $descuento_linea = ($subtotal_linea * $descuento) / 100;
                $subtotal_linea = $subtotal_linea - $descuento_linea;
                
                $subtotal_venta += $subtotal_linea;
                $descuento_total += $descuento_linea;
            }
            
            if($subtotal_venta <= 0){
                throw new Exception("El total de venta debe ser mayor que 0");
            }
            
            // Calcular IVA (19%)
            $iva = $subtotal_venta * 0.19;
            $total_venta = $subtotal_venta + $iva;
            
            // Insertar venta
            $sql_venta = "INSERT INTO ventas (fecha, cliente, usuario, total)
            VALUES (NOW(), " . ($cliente > 0 ? $cliente : "NULL") . ", $usuario, $total_venta)";
            
            if(!$conn->query($sql_venta)){
                throw new Exception("Error al crear venta: " . $conn->error);
            }
            
            $id_venta = $conn->insert_id;
            
            // Insertar detalles de venta
            foreach($productos as $idx => $id_inv){
                $id_inv = intval($id_inv);
                $cantidad = floatval($cantidades[$idx] ?? 0);
                $descuento = floatval($descuentos[$idx] ?? 0);
                
                if($cantidad <= 0 || $id_inv <= 0) continue;
                
                // Obtener precio
                $inv_sql = "SELECT p.precio_venta, i.stock FROM inventario i 
                JOIN productos p ON i.producto = p.id_producto 
                WHERE i.id_inventario = $id_inv";
                $inv_result = $conn->query($inv_sql);
                $inv_data = $inv_result->fetch_assoc();
                
                $precio = floatval($inv_data['precio_venta']);
                $subtotal_linea = $cantidad * $precio;
                $subtotal_linea = $subtotal_linea - (($subtotal_linea * $descuento) / 100);
                
                $sql_detalle = "INSERT INTO detalle_venta (venta, inventario, cantidad, precio, subtotal)
                VALUES ($id_venta, $id_inv, $cantidad, $precio, $subtotal_linea)";
                
                if(!$conn->query($sql_detalle)){
                    throw new Exception("Error al crear detalle: " . $conn->error);
                }
            }
            
            $conn->commit();
            header("Location: detalle.php?id=$id_venta&success=1");
            exit;
        } catch(Exception $e){
            $conn->rollback();
            $error = $e->getMessage();
        }
    }
}

// Obtener inventario nuevamente
$inventario_result = $conn->query("SELECT i.id_inventario, p.nom_producto, p.precio_venta, i.stock, 
t.nombre_talla, c.nombre_color FROM inventario i
JOIN productos p ON i.producto = p.id_producto
LEFT JOIN tallas t ON i.talla = t.id_talla
LEFT JOIN colores c ON i.color = c.id_color
WHERE i.stock > 0
ORDER BY p.nom_producto ASC");

// Obtener clientes nuevamente
$clientes_result = $conn->query("SELECT id_cliente, nombre FROM clientes ORDER BY nombre ASC");
?>

<div class="page-header">
    <h2>Crear Nueva Venta</h2>
    <p style="color: #6b7280;">Completa el formulario para registrar una nueva venta</p>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<style>
    .venta-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .productos-section {
        margin: 2rem 0;
    }
    
    .producto-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .producto-fila {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }
    
    .producto-fila input,
    .producto-fila select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.95rem;
    }
    
    .resumen {
        background: #f3f4f6;
        padding: 1.5rem;
        border-radius: 6px;
        margin: 2rem 0;
    }
    
    .resumen-fila {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        font-size: 1rem;
    }
    
    .resumen-fila.total {
        border-top: 2px solid #e5e7eb;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 600;
        font-size: 1.25rem;
        color: #2563eb;
    }
    
    .resumen-fila.iva {
        color: #ef4444;
        font-weight: 600;
    }
    
    button.agregar-producto {
        background-color: #10b981;
        margin-bottom: 2rem;
        width: 100%;
    }
    
    button.agregar-producto:hover {
        background-color: #059669;
    }
    
    .producto-remove {
        background-color: #ef4444;
        color: white;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    
    .producto-remove:hover {
        background-color: #dc2626;
    }
    
    @media (max-width: 1200px) {
        .producto-fila {
            grid-template-columns: 1.5fr 0.8fr 0.8fr 0.8fr 0.8fr auto;
        }
    }
    
    @media (max-width: 768px) {
        .venta-container {
            padding: 1rem;
        }
        
        .producto-item {
            padding: 0.75rem;
        }
        
        .producto-fila {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .producto-fila input,
        .producto-fila select {
            font-size: 0.9rem;
        }
        
        .producto-remove {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .resumen {
            padding: 1rem;
            margin: 1.5rem 0;
        }
        
        .resumen-fila {
            padding: 0.5rem 0;
            font-size: 0.95rem;
        }
        
        .resumen-fila.total {
            font-size: 1.1rem;
        }
        
        .page-header {
            margin-bottom: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .venta-container {
            padding: 0.75rem;
            border-radius: 4px;
        }
        
        .producto-item {
            padding: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .producto-fila {
            gap: 0.5rem;
        }
        
        .producto-fila input,
        .producto-fila select {
            padding: 0.4rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .resumen {
            padding: 0.75rem;
        }
        
        .resumen-fila {
            padding: 0.4rem 0;
            font-size: 0.9rem;
        }
        
        button.agregar-producto {
            padding: 0.6rem 0.75rem;
            font-size: 0.88rem;
        }
        
        .form-group label {
            font-size: 0.95rem;
        }
    }
</style>

<script>
    let productosAñadidos = 0;
    
    function agregarProducto() {
        const container = document.getElementById('productos-container');
        const index = productosAñadidos++;
        
        const html = `
            <div class="producto-item" id="producto-${index}">
                <div class="producto-fila">
                    <select name="producto[]" onchange="actualizarPrecios()" required>
                        <option value="">Seleccionar producto...</option>
                        <?php 
                        $inventario_result->data_seek(0);
                        while($row = $inventario_result->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $row['id_inventario']; ?>" data-precio="<?php echo $row['precio_venta']; ?>" data-stock="<?php echo $row['stock']; ?>">
                                <?php echo htmlspecialchars($row['nom_producto']); ?> - <?php echo htmlspecialchars($row['nombre_talla'] ?? 'S/T'); ?> - <?php echo htmlspecialchars($row['nombre_color'] ?? 'S/C'); ?> ($<?php echo number_format($row['precio_venta'], 2); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="cantidad[]" min="1" value="1" placeholder="Cantidad" onchange="actualizarPrecios()">
                    <input type="number" name="descuento[]" min="0" max="100" value="0" placeholder="Desc %" onchange="actualizarPrecios()">
                    <input type="number" name="subtotal[]" readonly style="background: #f0f0f0; cursor: not-allowed;" value="$0.00">
                    <button type="button" class="producto-remove" onclick="removerProducto(${index})">Remover</button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }
    
    function removerProducto(index) {
        const item = document.getElementById(`producto-${index}`);
        if(item) {
            item.remove();
            actualizarPrecios();
        }
    }
    
    function actualizarPrecios() {
        let subtotalGeneral = 0;
        let descuentoTotal = 0;
        
        const selects = document.querySelectorAll('select[name="producto[]"]');
        const cantidades = document.querySelectorAll('input[name="cantidad[]"]');
        const descuentos = document.querySelectorAll('input[name="descuento[]"]');
        const subtotales = document.querySelectorAll('input[name="subtotal[]"]');
        
        selects.forEach((select, index) => {
            const option = select.options[select.selectedIndex];
            const precio = parseFloat(option.dataset.precio) || 0;
            const cantidad = parseFloat(cantidades[index].value) || 0;
            const descuento = parseFloat(descuentos[index].value) || 0;
            
            let subtotal = precio * cantidad;
            const descuentoLinea = (subtotal * descuento) / 100;
            subtotal = subtotal - descuentoLinea;
            
            subtotales[index].value = '$' + subtotal.toFixed(2);
            subtotalGeneral += subtotal;
            descuentoTotal += descuentoLinea;
        });
        
        const iva = subtotalGeneral * 0.19;
        const totalGeneral = subtotalGeneral + iva;
        
        document.getElementById('subtotal-general').textContent = '$' + subtotalGeneral.toFixed(2);
        document.getElementById('descuento-general').textContent = '$' + descuentoTotal.toFixed(2);
        document.getElementById('iva-general').textContent = '$' + iva.toFixed(2);
        document.getElementById('total-general').textContent = '$' + totalGeneral.toFixed(2);
    }
    
    window.onload = function() {
        agregarProducto(); // Agregar una fila por defecto
    };
</script>

<form method="POST" class="venta-container" id="form-venta">
    <div class="form-group">
        <label for="cliente">Cliente</label>
        <select id="cliente" name="cliente">
            <option value="">Sin cliente registrado</option>
            <?php while($row = $clientes_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id_cliente']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="productos-section">
        <h3>Productos de la Venta</h3>
        <button type="button" class="btn agregar-producto" onclick="agregarProducto()">+ Agregar Producto</button>
        
        <div id="productos-container"></div>
    </div>
    
    <div class="resumen">
        <h3 style="margin-top: 0;">Resumen de Venta</h3>
        <div class="resumen-fila">
            <span>Subtotal:</span>
            <span id="subtotal-general">$0.00</span>
        </div>
        <div class="resumen-fila">
            <span>Descuentos Aplicados:</span>
            <span id="descuento-general">$0.00</span>
        </div>
        <div class="resumen-fila iva">
            <span>IVA (19%):</span>
            <span id="iva-general">$0.00</span>
        </div>
        <div class="resumen-fila total">
            <span>TOTAL:</span>
            <span id="total-general">$0.00</span>
        </div>
    </div>
    
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button type="submit" style="flex: 1; min-width: 150px;">Guardar Venta</button>
        <a href="index.php" class="btn" style="flex: 1; min-width: 150px; text-align: center; text-decoration: none; background-color: #6b7280;">Cancelar</a>
    </div>
</form>

<?php include("../includes/footer.php"); ?>
