-- =====================================================================
-- SISTEMA DE INVENTARIO - ARCHIVO SQL CONSOLIDADO
-- =====================================================================
-- Este archivo contiene todos los procedimientos almacenados, vistas y 
-- triggers necesarios para el manejo completo del sistema de inventario.
-- 
-- Componentes incluidos:
-- 1. VISTAS: Para consultas simplificadas de ingresos y salidas
-- 2. TRIGGERS: Para manejo automático de stock
-- 3. PROCEDIMIENTOS: Para consultas detalladas y reportes
-- =====================================================================

-- =====================================================================
-- SECCIÓN 1: VISTAS DEL SISTEMA
-- =====================================================================

-- ---------------------------------------------------------------------
-- VISTA: vista_ingresos
-- Propósito: Proporciona una vista simplificada de todos los ingresos
--           incluyendo información del proveedor y usuario responsable
-- Uso: Consultas rápidas para listados de ingresos con datos relacionados
-- ---------------------------------------------------------------------
CREATE OR REPLACE VIEW vista_ingresos AS
SELECT
	i.id_ingreso, 
	p.nombre AS nombre_proveedor,
    u.name AS nombre_usuario,
    i.n_factura,
    i.n_pedido,
    i.fecha_hora, 
    i.estado,
    i.total
FROM 
    ingresos i
LEFT JOIN 
    proveedores p ON i.id_proveedor = p.id_proveedor
LEFT JOIN 
    users u ON i.id_usuario= u.id
ORDER BY 
    i.id_ingreso DESC;

-- ---------------------------------------------------------------------
-- VISTA: vista_salidas
-- Propósito: Proporciona una vista simplificada de todas las salidas
--           incluyendo información de la unidad destino y usuario responsable
-- Uso: Consultas rápidas para listados de salidas con datos relacionados
-- ---------------------------------------------------------------------
CREATE OR REPLACE VIEW vista_salidas AS
SELECT 
    s.id_salida, 
    uni.nombre AS nombre_unidad,
    u.name AS nombre_usuario, 
    s.n_hoja_ruta,
    s.n_pedido,
    s.fecha_hora, 
    s.estado,
    s.total
FROM 
    salidas s
LEFT JOIN 
    unidades uni ON s.id_unidad = uni.id_unidad
LEFT JOIN 
    users u ON s.id_usuario = u.id
ORDER BY 
    s.id_salida DESC;

-- =====================================================================
-- SECCIÓN 2: TRIGGERS PARA CONTROL AUTOMÁTICO DE STOCK
-- =====================================================================

-- ---------------------------------------------------------------------
-- TRIGGER: tr_ingresostock
-- Propósito: Actualiza automáticamente el stock de productos cuando
--           se registra un nuevo ingreso en detalle_ingresos
-- Evento: AFTER INSERT en tabla detalle_ingresos
-- Funcionalidad: Suma la cantidad ingresada al stock actual del producto
-- ---------------------------------------------------------------------
DELIMITER $$

CREATE TRIGGER tr_ingresostock 
AFTER INSERT ON detalle_ingresos
FOR EACH ROW
BEGIN
    -- Actualiza el stock directamente al insertar en detalle_ingresos
    UPDATE productos 
    SET stock = stock + NEW.cantidad_original
    WHERE id_producto = NEW.id_producto;
END $$

DELIMITER ;

-- ---------------------------------------------------------------------
-- TRIGGER: tr_salidastock
-- Propósito: Actualiza automáticamente el stock de productos cuando
--           se registra una nueva salida en detalle_salidas
-- Evento: AFTER INSERT en tabla detalle_salidas
-- Funcionalidad: Resta la cantidad de salida del stock actual del producto
-- ---------------------------------------------------------------------
DELIMITER $$

CREATE TRIGGER tr_salidastock 
AFTER INSERT ON detalle_salidas
FOR EACH ROW 
BEGIN
    -- Actualizar el stock restando la cantidad del detalle de salida
    UPDATE productos 
    SET stock = stock - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
END $$

DELIMITER ;

-- =====================================================================
-- SECCIÓN 3: PROCEDIMIENTOS ALMACENADOS PARA CONSULTAS DETALLADAS
-- =====================================================================

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerDetalleSalida
-- Propósito: Obtiene el detalle completo de una salida específica
-- Parámetros: 
--   - id_salida (INT): ID de la salida a consultar
-- Retorna: Lista de productos, categorías, cantidades y costos de la salida
-- Uso: Generar reportes detallados de salidas específicas
-- ---------------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS obtenerDetalleSalida$$
CREATE PROCEDURE obtenerDetalleSalida(IN id_salida INT)
BEGIN
    SELECT 
        p.codigo AS codigo_producto,
        p.descripcion AS producto,
        c.codigo AS codigo_categoria,
        c.descripcion AS categoria,
        p.unidad,
        d.cantidad,
        d.costo_u,
        d.lote,
        d.id_salida 
    FROM detalle_salidas AS d
    INNER JOIN productos AS p 
        ON d.id_producto = p.id_producto
    INNER JOIN categorias AS c 
        ON p.id_categoria = c.id_categoria
    WHERE d.id_salida = id_salida;
END$$

DELIMITER ;

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerDetalleIngreso
-- Propósito: Obtiene el detalle completo de un ingreso específico
-- Parámetros: 
--   - id_ingreso (INT): ID del ingreso a consultar
-- Retorna: Lista de productos, categorías, cantidades originales/disponibles y costos
-- Uso: Generar reportes detallados de ingresos específicos
-- ---------------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS obtenerDetalleIngreso$$
CREATE PROCEDURE obtenerDetalleIngreso(IN id_ingreso INT)
BEGIN
    SELECT 
        p.codigo AS codigo_producto,
        p.descripcion AS producto,
        c.codigo AS codigo_categoria,
        c.descripcion AS categoria,
        p.unidad,
        d.cantidad_original,
        d.cantidad_disponible,
        d.costo_u,
        d.lote,
        d.id_ingreso 
    FROM detalle_ingresos AS d
    INNER JOIN productos AS p 
        ON d.id_producto = p.id_producto
    INNER JOIN categorias AS c 
        ON p.id_categoria = c.id_categoria
    WHERE d.id_ingreso = id_ingreso;
END$$

DELIMITER ;

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerMovimientos
-- Propósito: Genera un reporte completo de movimientos de inventario
--           en un rango de fechas específico, incluyendo saldos iniciales
--           y finales por producto y lote
-- Parámetros: 
--   - fecha_inicio (DATE): Fecha de inicio del período a consultar
--   - fecha_fin (DATE): Fecha de fin del período a consultar
-- Retorna: Reporte detallado con saldos, ingresos, salidas y totales
-- Uso: Análisis de movimientos de inventario y control de stock por períodos
-- ---------------------------------------------------------------------
DELIMITER $$

DROP PROCEDURE IF EXISTS obtenerMovimientos$$
CREATE PROCEDURE obtenerMovimientos(
    IN fecha_inicio DATE,
    IN fecha_fin DATE
)
BEGIN
    -- Obtención de los movimientos de ingresos y salidas
    WITH Movimientos AS (
        -- Ingresos dentro del rango de fechas
        SELECT 
            di.id_producto, 
            di.lote, 
            DATE(i.fecha_hora) AS Fecha_Movimiento, 
            'Ingreso' AS tipo, 
            di.cantidad_original AS cantidad, 
            di.costo_u
        FROM detalle_ingresos di
        JOIN ingresos i ON di.id_ingreso = i.id_ingreso
        WHERE DATE(i.fecha_hora) BETWEEN fecha_inicio AND fecha_fin

        UNION ALL

        -- Salidas dentro del rango de fechas
        SELECT 
            ds.id_producto, 
            ds.lote, 
            DATE(s.fecha_hora) AS Fecha_Movimiento, 
            'Salida' AS tipo, 
            ds.cantidad, 
            ds.costo_u
        FROM detalle_salidas ds
        JOIN salidas s ON ds.id_salida = s.id_salida
        WHERE DATE(s.fecha_hora) BETWEEN fecha_inicio AND fecha_fin
    ),
    -- Cálculo del saldo inicial (mantener lógica original)
    SaldoInicial AS (
        SELECT 
            di.id_producto, 
            di.lote,
            -- Saldo inicial: cantidad original menos salidas previas a la fecha de inicio
            COALESCE(di.cantidad_original, 0) - 
            COALESCE(SUM(CASE WHEN s.fecha_hora < fecha_inicio THEN ds.cantidad ELSE 0 END), 0) AS saldo_inicial,
            -- Costo inicial: costo unitario multiplicado por cantidad original menos costos de salidas previas
            COALESCE(di.cantidad_original * di.costo_u, 0) - 
            COALESCE(SUM(CASE WHEN s.fecha_hora < fecha_inicio THEN ds.cantidad * ds.costo_u ELSE 0 END), 0) AS costo_inicial
        FROM detalle_ingresos di
        JOIN ingresos i ON di.id_ingreso = i.id_ingreso
        LEFT JOIN detalle_salidas ds ON di.id_producto = ds.id_producto AND di.lote = ds.lote
        LEFT JOIN salidas s ON ds.id_salida = s.id_salida
        WHERE i.fecha_hora < fecha_inicio -- Fecha de corte para ingresos
        GROUP BY di.id_producto, di.lote, di.cantidad_original, di.costo_u
    )
    -- Consulta principal: Resultados detallados por producto y lote
    SELECT 
        p.codigo AS Codigo,
        p.descripcion AS Producto,
        COALESCE(si.lote, m.lote) AS Lote,
        MAX(COALESCE(m.Fecha_Movimiento, '')) AS Fecha_Movimiento,
        MAX(COALESCE(si.saldo_inicial, 0)) AS Saldo_Inicial,
        MAX(COALESCE(si.costo_inicial, 0)) AS Costo_Inicial,
        SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad ELSE 0 END) AS Ingresos_Cantidad,
        SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad * m.costo_u ELSE 0 END) AS Ingresos_Costo,
        SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad ELSE 0 END) AS Salidas_Cantidad,
        SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad * m.costo_u ELSE 0 END) AS Salidas_Costo,
        (MAX(COALESCE(si.saldo_inicial, 0)) + 
         SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad ELSE 0 END) - 
         SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad ELSE 0 END)) AS Saldo_Final,
        (MAX(COALESCE(si.costo_inicial, 0)) + 
         SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad * m.costo_u ELSE 0 END) - 
         SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad * m.costo_u ELSE 0 END)) AS Costo_Final
    FROM productos p
    LEFT JOIN SaldoInicial si ON p.id_producto = si.id_producto
    LEFT JOIN Movimientos m ON p.id_producto = m.id_producto
    WHERE 
        -- Mostrar productos con saldo inicial o movimientos dentro del rango
        EXISTS (
            SELECT 1
            FROM SaldoInicial si2
            WHERE si2.id_producto = p.id_producto
        )
        OR EXISTS (
            SELECT 1
            FROM Movimientos m2
            WHERE m2.id_producto = p.id_producto
        )
    GROUP BY p.codigo, p.descripcion, COALESCE(si.lote, m.lote)

    UNION ALL

    -- Total general
    SELECT 
        'TOTAL' AS Codigo,
        'Total General' AS Producto,
        NULL AS Lote,
        NULL AS Fecha_Movimiento,
        SUM(COALESCE(si.saldo_inicial, 0)) AS Saldo_Inicial,
        SUM(COALESCE(si.costo_inicial, 0)) AS Costo_Inicial,
        SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad ELSE 0 END) AS Ingresos_Cantidad,
        SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad * m.costo_u ELSE 0 END) AS Ingresos_Costo,
        SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad ELSE 0 END) AS Salidas_Cantidad,
        SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad * m.costo_u ELSE 0 END) AS Salidas_Costo,
        (SUM(COALESCE(si.saldo_inicial, 0)) + 
         SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad ELSE 0 END) - 
         SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad ELSE 0 END)) AS Saldo_Final,
        (SUM(COALESCE(si.costo_inicial, 0)) + 
         SUM(CASE WHEN m.tipo = 'Ingreso' THEN m.cantidad * m.costo_u ELSE 0 END) - 
         SUM(CASE WHEN m.tipo = 'Salida' THEN m.cantidad * m.costo_u ELSE 0 END)) AS Costo_Final
    FROM productos p
    LEFT JOIN SaldoInicial si ON p.id_producto = si.id_producto
    LEFT JOIN Movimientos m ON p.id_producto = m.id_producto;
END$$

DELIMITER ;

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerSaldoPorLote
-- Propósito: Obtiene el saldo actual de productos agrupados por lote
--           con opción de filtrar por categoría y fecha límite
-- Parámetros: 
--   - categoria_id (INT): ID de categoría (NULL para todas las categorías)
--   - fecha_fin (DATE): Fecha límite para el cálculo (NULL para fecha actual)
-- Retorna: Saldo detallado por lote con cantidades y valores actuales
-- Uso: Control de inventario por lotes y valorización de stock
-- ---------------------------------------------------------------------
DELIMITER $$

DROP PROCEDURE IF EXISTS obtenerSaldoPorLote$$
CREATE PROCEDURE obtenerSaldoPorLote(
    IN categoria_id INT,
    IN fecha_fin DATE
)
BEGIN
    SELECT 
		c.codigo as codigo_categoria,
        c.descripcion AS categoria,
        p.codigo AS codigo_producto,
        p.descripcion AS producto,
        p.unidad,
        di.lote,
        di.cantidad_original - COALESCE(SUM(ds.cantidad), 0) AS cantidad_actual,
        di.costo_u,
        (di.cantidad_original - COALESCE(SUM(ds.cantidad), 0)) * di.costo_u AS costo_total_lote
    FROM categorias c
    INNER JOIN productos p ON c.id_categoria = p.id_categoria
    INNER JOIN detalle_ingresos di ON p.id_producto = di.id_producto
    LEFT JOIN detalle_salidas ds ON di.id_producto = ds.id_producto AND di.lote = ds.lote
    LEFT JOIN ingresos i ON di.id_ingreso = i.id_ingreso
    LEFT JOIN salidas s ON ds.id_salida = s.id_salida
    WHERE (categoria_id IS NULL OR c.id_categoria = categoria_id)
    AND (fecha_fin IS NULL OR i.fecha_hora IS NULL OR DATE(i.fecha_hora) <= fecha_fin)
    AND (fecha_fin IS NULL OR s.fecha_hora IS NULL OR DATE(s.fecha_hora) <= fecha_fin)
    GROUP BY c.descripcion, p.codigo, p.descripcion, di.lote, di.cantidad_original, di.costo_u
    ORDER BY c.descripcion, p.descripcion, di.lote;
END $$

DELIMITER ;

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerTotalesPorProducto
-- Propósito: Obtiene totales consolidados por producto (suma de todos los lotes)
--           con opción de filtrar por categoría y fecha límite
-- Parámetros: 
--   - categoria_id (INT): ID de categoría (NULL para todas las categorías)
--   - fecha_fin (DATE): Fecha límite para el cálculo (NULL para fecha actual)
-- Retorna: Totales por producto con cantidad y valor actual consolidado
-- Uso: Resumen ejecutivo de inventario por producto
-- ---------------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS obtenerTotalesPorProducto$$
CREATE PROCEDURE obtenerTotalesPorProducto(
    IN categoria_id INT,
    IN fecha_fin DATE
)
BEGIN
    SELECT 
        p.codigo AS codigo_producto,
        p.descripcion AS producto,
        SUM(di.cantidad_original - COALESCE(ds.cantidad, 0)) AS total_cantidad_actual,
        SUM((di.cantidad_original - COALESCE(ds.cantidad, 0)) * di.costo_u) AS total_valor_actual
    FROM categorias c
    INNER JOIN productos p ON c.id_categoria = p.id_categoria
    INNER JOIN detalle_ingresos di ON p.id_producto = di.id_producto
    LEFT JOIN (
        SELECT id_producto, lote, SUM(cantidad) AS cantidad
        FROM detalle_salidas
        GROUP BY id_producto, lote
    ) ds ON di.id_producto = ds.id_producto AND di.lote = ds.lote
    LEFT JOIN ingresos i ON di.id_ingreso = i.id_ingreso
    WHERE (categoria_id IS NULL OR c.id_categoria = categoria_id)
    AND (fecha_fin IS NULL OR i.fecha_hora IS NULL OR DATE(i.fecha_hora) <= fecha_fin)
    GROUP BY p.codigo, p.descripcion
    ORDER BY p.descripcion;
END $$

DELIMITER ;

-- ---------------------------------------------------------------------
-- PROCEDIMIENTO: obtenerTotalesPorCategoria
-- Propósito: Obtiene totales consolidados por categoría con gran total
--           usando WITH ROLLUP para generar subtotales automáticamente
-- Parámetros: 
--   - categoria_id (INT): ID de categoría (NULL para todas las categorías)
--   - fecha_fin (DATE): Fecha límite para el cálculo (NULL para fecha actual)
-- Retorna: Totales por categoría más total general del inventario
-- Uso: Resumen ejecutivo de inventario por categoría y total general
-- ---------------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS obtenerTotalesPorCategoria$$
CREATE PROCEDURE obtenerTotalesPorCategoria(
    IN categoria_id INT,
    IN fecha_fin DATE
)
BEGIN
    -- Totales por categoría con WITH ROLLUP
    SELECT 
        IFNULL(c.codigo, 'TOTAL') AS codigo_categoria,
        IFNULL(c.descripcion, 'TOTAL GENERAL') AS categoria,
        SUM(di.cantidad_original - COALESCE(ds.cantidad, 0)) AS total_cantidad_actual,
        SUM((di.cantidad_original - COALESCE(ds.cantidad, 0)) * di.costo_u) AS total_valor_actual
    FROM categorias c
    INNER JOIN productos p ON c.id_categoria = p.id_categoria
    INNER JOIN detalle_ingresos di ON p.id_producto = di.id_producto
    LEFT JOIN (
        SELECT ds.id_producto, ds.lote, SUM(ds.cantidad) AS cantidad
        FROM detalle_salidas ds
        GROUP BY ds.id_producto, ds.lote
    ) ds ON di.id_producto = ds.id_producto AND di.lote = ds.lote
    LEFT JOIN ingresos i ON di.id_ingreso = i.id_ingreso
    WHERE (categoria_id IS NULL OR c.id_categoria = categoria_id)
    AND (fecha_fin IS NULL OR i.fecha_hora IS NULL OR DATE(i.fecha_hora) <= fecha_fin)
    GROUP BY c.codigo, c.descripcion WITH ROLLUP;
END $$
DELIMITER ;

-- =====================================================================
-- SECCIÓN 4: EJEMPLOS DE USO Y LLAMADAS DE PRUEBA
-- =====================================================================

-- Ejemplo: Consultar movimientos de febrero 2025
-- CALL obtenerMovimientos('2025-02-01', '2025-02-28');

-- Ejemplo: Consultar saldo actual sin filtros
-- CALL obtenerSaldoPorLote(NULL, '2025-02-27');
-- CALL obtenerTotalesPorProducto(NULL, '2025-02-27');
-- CALL obtenerTotalesPorCategoria(NULL, '2025-02-27');

-- Ejemplo: Consultar detalle de un ingreso específico
-- CALL obtenerDetalleIngreso(1);

-- Ejemplo: Consultar detalle de una salida específica
-- CALL obtenerDetalleSalida(1);

-- =====================================================================
-- FIN DEL ARCHIVO
-- =====================================================================