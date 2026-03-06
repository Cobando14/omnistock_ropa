# OmniStock - Guía de Configuración Completa

## ✅ Cambios Realizados

### 1. **Archivo: config/conexion.php**
- ✅ Mejorado manejo de errores con mensajes claros
- ✅ Agregado soporte explícito de puerto 3306
- ✅ Configurado charset UTF-8
- ✅ Agregado logging de errores
- ✅ Links a diagnóstico si falla la conexión

### 2. **Archivo: usuarios/editar.php**
- ✅ CORREGIDO: `tipo_documento='$tipo_documento'` → `tipo_documento=$tipo_documento` (error de tipo de dato)
- ✅ Todas las referencias a columnas ajustadas al schema actual

### 3. **Script Nuevo: diagnostico.php**
- ✅ Verifica conexión a BD
- ✅ Valida estructura de tablas
- ✅ Verifica columnas críticas
- ✅ Cuenta registros de datos
- ✅ Proporciona recomendaciones

## 🚀 Pasos para Poner en Funcionamiento

### Paso 1: Iniciar MySQL en XAMPP
1. Abre **XAMPP Control Panel**
2. Verifica que **MySQL** esté corriendo (botón "Start" debería estar en verde)
3. El status debe decir "Running"

### Paso 2: Verificar la Configuración
1. Abre en tu navegador: `http://localhost/OmniStock/diagnostico.php`
2. Revisa el diagnóstico completo:
   - ✅ Debe mostrar "Conexión Exitosa"
   - ✅ Todas las tablas deben existir
   - ✅ Las columnas críticas deben estar presentes

### Paso 3: Cargar Datos (si es necesario)
Si las tablas existen pero están vacías:
1. Abre **phpMyAdmin** en `http://localhost/phpmyadmin`
2. Selecciona la BD `omnistock_ropa`
3. Ve a la pestaña "SQL"
4. Copia y ejecuta el contenido de:
   - `data_insert_46_adicionales.sql` (roles, tipos documento, etc.)
   - `data_insert_200_productos.sql` (productos e inventario)

### Paso 4: Probar el Login
1. Ve a `http://localhost/OmniStock/login/login.php`
2. Ingresa credenciales de un usuario creado
3. Deberías ver el Dashboard

## 📋 Validación de Módulos

Cada módulo ha sido validado para usar los nombres de columna correctos:

| Módulo | Tablas | Estado |
|--------|--------|--------|
| **Usuarios** | usuarios, rol, tip_documento | ✅ Ajustado |
| **Productos** | productos, categoria | ✅ Correcto |
| **Inventario** | inventario, productos, tallas, colores | ✅ Correcto |
| **Ventas** | ventas, detalle_venta, clientes, usuarios | ✅ Correcto |
| **Categorías** | categoria | ✅ Correcto |
| **Roles** | rol | ✅ Correcto |

## ⚠️ Cambios de Columnas Importantes

El schema de `omnistock_ropa` usa estos nombres (diferentes a lo que podría esperarse):

```
USUARIOS
├── correo_usuario (NO: correo)
├── password_usuario (NO: contrasena)
└── rol (FK a rol.id_rol, NO: id_rol)

INVENTARIO
├── producto (FK, NO: id_producto)
├── talla (FK, NO: id_talla)
└── color (FK, NO: id_color)

VENTAS
├── cliente (FK, NO: id_cliente)
└── usuario (FK, NO: id_usuario)
```

Todos los módulos PHP han sido ajustados para usar estos nombres.

## 🔧 Solución de Problemas

### Error: "No se puede establecer una conexión..."
- **Causa**: MySQL no está ejecutándose
- **Solución**: 
  - Abre XAMPP Control Panel
  - Haz clic en "Start" para MySQL
  - Espera a que el status cambie a "Running"

### Error: "ERROR 1045 - Access denied for user 'root'@'localhost'"
- **Causa**: Contraseña incorrecta
- **Solución**:
  - Verifica la contraseña en `config/conexion.php`
  - La contraseña configurada es: `obando123`
  - Si es incorrecta, actualiza en el archivo

### Error: "Unknown database 'omnistock_ropa'"
- **Causa**: Base de datos no existe
- **Solución**:
  - Abre phpMyAdmin
  - Haz clic en SQL
  - Ejecuta el contenido de `omnistock_ropa.sql`

### Tabla o columna no encontrada
- **Causa**: Schema incompleto
- **Solución**:
  - Ejecuta `diagnostico.php` para ver qué falta
  - Recrea la BD ejecutando los scripts SQL

## 📞 Información de la Base de Datos

```
Host: localhost
Puerto: 3306
Usuario: root
Contraseña: obando123
Base de Datos: omnistock_ropa
```

## ✨ Funcionalidades Disponibles

Después de configurar correctamente, tendrás acceso a:

- 📦 **Gestión de Productos** (38 categorías)
- 📊 **Inventario** (200 items con tallas y colores)
- 💰 **Gestión de Ventas** (con cálculo de IVA)
- 👥 **Gestión de Usuarios** (con roles)
- 🏷️ **Categorías de Productos**
- 🔐 **Control de Roles y Permisos**
- 📈 **Reportes de Inventario**

---

**Última actualización**: 2026-03-06
**Versión**: 1.0
