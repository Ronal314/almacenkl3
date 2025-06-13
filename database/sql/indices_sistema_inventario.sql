-- =====================================================================
-- SISTEMA DE INVENTARIO - ÍNDICES PARA OPTIMIZACIÓN DE RENDIMIENTO
-- =====================================================================
-- Este archivo contiene todos los índices necesarios para optimizar
-- el rendimiento de las consultas del sistema de inventario.
--
-- IMPORTANTE: 
-- - Ejecutar después de crear las tablas principales
-- - Los índices mejoran las consultas SELECT pero pueden ralentizar INSERT/UPDATE
-- - Se recomienda ejecutar durante horarios de bajo tráfico
-- - Monitorear el rendimiento después de la implementación
-- =====================================================================

-- =====================================================================
-- SECCIÓN 1: ÍNDICES PARA TABLA PRODUCTOS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por código de producto (búsquedas frecuentes por código)
-- Usado en: reportes, consultas de detalle, búsquedas de usuario
-- ---------------------------------------------------------------------
CREATE INDEX idx_productos_codigo 
ON productos(codigo);

-- ---------------------------------------------------------------------
-- Índice por categoría (filtros por categoría muy comunes)
-- Usado en: procedimientos de saldo por categoría, reportes filtrados
-- ---------------------------------------------------------------------
CREATE INDEX idx_productos_categoria 
ON productos(id_categoria);

-- ---------------------------------------------------------------------
-- Índice compuesto: categoría + código (consultas filtradas por categoría)
-- Usado en: obtenerSaldoPorLote, obtenerTotalesPorCategoria
-- ---------------------------------------------------------------------
CREATE INDEX idx_productos_categoria_codigo 
ON productos(id_categoria, codigo);

-- ---------------------------------------------------------------------
-- Índice por descripción (búsquedas por nombre de producto)
-- Usado en: búsquedas de usuario, autocomplete
-- ---------------------------------------------------------------------
CREATE INDEX idx_productos_descripcion 
ON productos(descripcion);

-- =====================================================================
-- SECCIÓN 2: ÍNDICES PARA TABLA INGRESOS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por fecha (consultas por rango de fechas muy frecuentes)
-- Usado en: obtenerMovimientos, reportes por período
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_fecha 
ON ingresos(fecha_hora);

-- ---------------------------------------------------------------------
-- Índice por proveedor (consultas por proveedor específico)
-- Usado en: vista_ingresos, reportes por proveedor
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_proveedor 
ON ingresos(id_proveedor);

-- ---------------------------------------------------------------------
-- Índice por usuario (auditoría y control por usuario)
-- Usado en: vista_ingresos, reportes de auditoría
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_usuario 
ON ingresos(id_usuario);

-- ---------------------------------------------------------------------
-- Índice por estado (filtros por estado del ingreso)
-- Usado en: consultas administrativas, control de estados
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_estado 
ON ingresos(estado);

-- ---------------------------------------------------------------------
-- Índice compuesto: fecha + estado (consultas filtradas por período y estado)
-- Usado en: reportes administrativos avanzados
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_fecha_estado 
ON ingresos(fecha_hora, estado);

-- =====================================================================
-- SECCIÓN 3: ÍNDICES PARA TABLA SALIDAS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por fecha (consultas por rango de fechas muy frecuentes)
-- Usado en: obtenerMovimientos, reportes por período
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_fecha 
ON salidas(fecha_hora);

-- ---------------------------------------------------------------------
-- Índice por unidad destino (consultas por unidad específica)
-- Usado en: vista_salidas, reportes por unidad
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_unidad 
ON salidas(id_unidad);

-- ---------------------------------------------------------------------
-- Índice por usuario (auditoría y control por usuario)
-- Usado en: vista_salidas, reportes de auditoría
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_usuario 
ON salidas(id_usuario);

-- ---------------------------------------------------------------------
-- Índice por estado (filtros por estado de la salida)
-- Usado en: consultas administrativas, control de estados
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_estado 
ON salidas(estado);

-- ---------------------------------------------------------------------
-- Índice compuesto: fecha + estado (consultas filtradas por período y estado)
-- Usado en: reportes administrativos avanzados
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_fecha_estado 
ON salidas(fecha_hora, estado);

-- =====================================================================
-- SECCIÓN 4: ÍNDICES PARA TABLA DETALLE_INGRESOS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por ingreso (JOIN muy frecuente con tabla ingresos)
-- Usado en: obtenerDetalleIngreso, obtenerMovimientos
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_ingresos_ingreso 
ON detalle_ingresos(id_ingreso);

-- ---------------------------------------------------------------------
-- Índice por producto (JOIN frecuente con tabla productos)
-- Usado en: todos los procedimientos de consulta de inventario
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_ingresos_producto 
ON detalle_ingresos(id_producto);

-- ---------------------------------------------------------------------
-- Índice compuesto: producto + lote (consultas de saldo por lote)
-- Usado en: obtenerSaldoPorLote, obtenerMovimientos, cálculos de stock
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_ingresos_producto_lote 
ON detalle_ingresos(id_producto, lote);

-- ---------------------------------------------------------------------
-- Índice por lote (búsquedas específicas por lote)
-- Usado en: trazabilidad, control de calidad
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_ingresos_lote 
ON detalle_ingresos(lote);

-- ---------------------------------------------------------------------
-- Índice compuesto: ingreso + producto (optimización de JOINs múltiples)
-- Usado en: consultas complejas que involucran ambas relaciones
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_ingresos_ingreso_producto 
ON detalle_ingresos(id_ingreso, id_producto);

-- =====================================================================
-- SECCIÓN 5: ÍNDICES PARA TABLA DETALLE_SALIDAS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por salida (JOIN muy frecuente con tabla salidas)
-- Usado en: obtenerDetalleSalida, obtenerMovimientos
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_salidas_salida 
ON detalle_salidas(id_salida);

-- ---------------------------------------------------------------------
-- Índice por producto (JOIN frecuente con tabla productos)
-- Usado en: todos los procedimientos de consulta de inventario
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_salidas_producto 
ON detalle_salidas(id_producto);

-- ---------------------------------------------------------------------
-- Índice compuesto: producto + lote (consultas de saldo por lote)
-- Usado en: obtenerSaldoPorLote, obtenerMovimientos, cálculos de stock
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_salidas_producto_lote 
ON detalle_salidas(id_producto, lote);

-- ---------------------------------------------------------------------
-- Índice por lote (búsquedas específicas por lote)
-- Usado en: trazabilidad, control de calidad
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_salidas_lote 
ON detalle_salidas(lote);

-- ---------------------------------------------------------------------
-- Índice compuesto: salida + producto (optimización de JOINs múltiples)
-- Usado en: consultas complejas que involucran ambas relaciones
-- ---------------------------------------------------------------------
CREATE INDEX idx_detalle_salidas_salida_producto 
ON detalle_salidas(id_salida, id_producto);

-- =====================================================================
-- SECCIÓN 6: ÍNDICES PARA TABLA CATEGORIAS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por código de categoría (búsquedas por código)
-- Usado en: consultas administrativas, reportes
-- ---------------------------------------------------------------------
CREATE INDEX idx_categorias_codigo 
ON categorias(codigo);

-- ---------------------------------------------------------------------
-- Índice por descripción de categoría (búsquedas por nombre)
-- Usado en: búsquedas de usuario, autocomplete
-- ---------------------------------------------------------------------
CREATE INDEX idx_categorias_descripcion 
ON categorias(descripcion);

-- =====================================================================
-- SECCIÓN 7: ÍNDICES PARA TABLA PROVEEDORES
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por nombre de proveedor (búsquedas y ordenamientos)
-- Usado en: vista_ingresos, reportes por proveedor
-- ---------------------------------------------------------------------
CREATE INDEX idx_proveedores_nombre 
ON proveedores(nombre);

-- =====================================================================
-- SECCIÓN 8: ÍNDICES PARA TABLA UNIDADES
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por nombre de unidad (búsquedas y ordenamientos)
-- Usado en: vista_salidas, reportes por unidad
-- ---------------------------------------------------------------------
CREATE INDEX idx_unidades_nombre 
ON unidades(nombre);

-- =====================================================================
-- SECCIÓN 9: ÍNDICES PARA TABLA USERS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice por nombre de usuario (búsquedas y ordenamientos)
-- Usado en: vistas de ingresos y salidas, auditoría
-- ---------------------------------------------------------------------
CREATE INDEX idx_users_name 
ON users(name);


-- =====================================================================
-- SECCIÓN 10: ÍNDICES COMPUESTOS ESPECIALES PARA CONSULTAS COMPLEJAS
-- =====================================================================

-- ---------------------------------------------------------------------
-- Índice para optimizar cálculo de saldo inicial en obtenerMovimientos
-- Combina las condiciones más frecuentes en la consulta de saldo inicial
-- ---------------------------------------------------------------------
CREATE INDEX idx_saldo_inicial_optimizado 
ON detalle_ingresos(id_producto, lote, cantidad_original, costo_u);

-- ---------------------------------------------------------------------
-- Índice para optimizar JOINs en consultas de movimientos
-- Usado en: obtenerMovimientos (condición de fecha en ingresos)
-- ---------------------------------------------------------------------
CREATE INDEX idx_ingresos_detalle_fecha 
ON ingresos(id_ingreso, fecha_hora);

-- ---------------------------------------------------------------------
-- Índice para optimizar JOINs en consultas de movimientos
-- Usado en: obtenerMovimientos (condición de fecha en salidas)
-- ---------------------------------------------------------------------
CREATE INDEX idx_salidas_detalle_fecha 
ON salidas(id_salida, fecha_hora);

-- =====================================================================
-- SECCIÓN 11: COMANDOS DE MANTENIMIENTO Y MONITOREO
-- =====================================================================

-- ---------------------------------------------------------------------
-- Consulta para verificar el uso de índices (ejecutar después de implementar)
-- ---------------------------------------------------------------------
/*
-- Verificar estadísticas de uso de índices
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    SEQ_IN_INDEX,
    COLUMN_NAME,
    CARDINALITY
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('productos', 'ingresos', 'salidas', 'detalle_ingresos', 'detalle_salidas')
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;
*/

-- ---------------------------------------------------------------------
-- Consulta para monitorear el tamaño de los índices
-- ---------------------------------------------------------------------
/*
-- Verificar tamaño de índices
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    ROUND(((INDEX_LENGTH) / 1024 / 1024), 2) AS 'Index Size (MB)'
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('productos', 'ingresos', 'salidas', 'detalle_ingresos', 'detalle_salidas')
ORDER BY INDEX_LENGTH DESC;
*/

-- =====================================================================
-- NOTAS IMPORTANTES PARA EL ADMINISTRADOR DE BASE DE DATOS
-- =====================================================================

/*
RECOMENDACIONES POST-IMPLEMENTACIÓN:

1. MONITOREO:
   - Ejecutar ANALYZE TABLE después de cargas masivas de datos
   - Monitorear el plan de ejecución de consultas frecuentes
   - Revisar logs de consultas lentas

2. MANTENIMIENTO:
   - Programar OPTIMIZE TABLE mensualmente para tablas con muchas modificaciones
   - Monitorear el crecimiento del espacio de índices
   - Evaluar la efectividad de índices poco utilizados

3. AJUSTES SEGÚN CARGA:
   - Si hay más consultas de lectura: mantener todos los índices
   - Si hay muchas operaciones de escritura: considerar eliminar índices menos críticos
   - Ajustar based en patrones reales de uso

4. ÍNDICES CANDIDATOS PARA ELIMINACIÓN SI HAY PROBLEMAS DE RENDIMIENTO:
   - idx_productos_descripcion (si las búsquedas por texto son raras)
   - idx_categorias_descripcion (si las búsquedas por nombre de categoría son raras)
   - Índices que aparezcan como no utilizados en las estadísticas

5. CONSIDERACIONES ESPECIALES:
   - Los índices en columnas de fecha son críticos para el rendimiento
   - Los índices compuestos (producto, lote) son esenciales para cálculos de stock
   - Monitorear especialmente el rendimiento del procedimiento obtenerMovimientos
*/

-- =====================================================================
-- FIN DEL ARCHIVO DE ÍNDICES
-- =====================================================================