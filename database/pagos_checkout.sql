-- =====================================================
-- ACTUALIZACIÓN DE BASE DE DATOS PARA SISTEMA DE PAGOS
-- CasasViaMar - Sistema de Checkout Completo
-- =====================================================

-- 1. AGREGAR CAMPOS DE PAGO A TABLA RESERVAS
ALTER TABLE `reservas` 
ADD COLUMN IF NOT EXISTS `estado_pago` ENUM('pendiente', 'pagado', 'reembolsado', 'fallido') NOT NULL DEFAULT 'pendiente' AFTER `estado`,
ADD COLUMN IF NOT EXISTS `id_transaccion` VARCHAR(100) NULL COMMENT 'ID de transacción PayPal' AFTER `estado_pago`,
ADD COLUMN IF NOT EXISTS `comprobante_url` VARCHAR(255) NULL COMMENT 'URL del comprobante de pago' AFTER `id_transaccion`,
ADD COLUMN IF NOT EXISTS `metodo_pago` VARCHAR(50) NULL COMMENT 'paypal, tarjeta, transferencia' AFTER `comprobante_url`,
ADD COLUMN IF NOT EXISTS `fecha_pago` DATETIME NULL COMMENT 'Fecha y hora del pago' AFTER `metodo_pago`,
ADD COLUMN IF NOT EXISTS `monto_subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Subtotal (noches)' AFTER `monto`,
ADD COLUMN IF NOT EXISTS `tarifa_limpieza` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Tarifa de limpieza' AFTER `monto_subtotal`,
ADD COLUMN IF NOT EXISTS `tarifa_servicio` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Comisión de la plataforma' AFTER `tarifa_limpieza`,
ADD COLUMN IF NOT EXISTS `monto_anfitrion` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Monto a pagar al dueño de la casa' AFTER `tarifa_servicio`,
ADD COLUMN IF NOT EXISTS `email_pagador` VARCHAR(100) NULL COMMENT 'Email del pagador PayPal' AFTER `monto_anfitrion`,
ADD COLUMN IF NOT EXISTS `terminos_aceptados` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 si aceptó términos' AFTER `email_pagador`,
ADD COLUMN IF NOT EXISTS `fecha_aceptacion_terminos` DATETIME NULL AFTER `terminos_aceptados`;

-- 2. AGREGAR CAMPOS DE CUENTA BANCARIA/PAYPAL A USUARIOS (ANFITRIONES)
ALTER TABLE `usuarios`
ADD COLUMN IF NOT EXISTS `cuenta_paypal` VARCHAR(100) NULL COMMENT 'Email de PayPal del anfitrión' AFTER `foto`,
ADD COLUMN IF NOT EXISTS `cuenta_bancaria` VARCHAR(50) NULL COMMENT 'CLABE interbancaria' AFTER `cuenta_paypal`,
ADD COLUMN IF NOT EXISTS `banco` VARCHAR(50) NULL COMMENT 'Nombre del banco' AFTER `cuenta_bancaria`,
ADD COLUMN IF NOT EXISTS `titular_cuenta` VARCHAR(100) NULL COMMENT 'Nombre del titular' AFTER `banco`,
ADD COLUMN IF NOT EXISTS `es_anfitrion` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 si es dueño de propiedades' AFTER `titular_cuenta`;

-- 3. AGREGAR CAMPO DE PROPIETARIO A HABITACIONES
ALTER TABLE `habitaciones`
ADD COLUMN IF NOT EXISTS `id_propietario` INT NULL COMMENT 'ID del usuario dueño de la propiedad' AFTER `id`,
ADD COLUMN IF NOT EXISTS `comision_plataforma` DECIMAL(5,2) NOT NULL DEFAULT 12.00 COMMENT 'Porcentaje de comisión' AFTER `tarifa_limpieza`;

-- 4. CREAR TABLA DE TRANSACCIONES PARA HISTORIAL DETALLADO
CREATE TABLE IF NOT EXISTS `transacciones_pago` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_reserva` INT NOT NULL,
    `id_transaccion_paypal` VARCHAR(100) NOT NULL,
    `estado` ENUM('CREATED', 'SAVED', 'APPROVED', 'VOIDED', 'COMPLETED', 'PAYER_ACTION_REQUIRED') NOT NULL,
    `monto_total` DECIMAL(10,2) NOT NULL,
    `moneda` VARCHAR(3) NOT NULL DEFAULT 'MXN',
    `email_pagador` VARCHAR(100) NULL,
    `nombre_pagador` VARCHAR(100) NULL,
    `metodo` VARCHAR(50) NULL COMMENT 'PayPal, Card, etc',
    `datos_raw` JSON NULL COMMENT 'Respuesta completa de PayPal',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `fecha_actualizacion` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_reserva` (`id_reserva`),
    INDEX `idx_transaccion` (`id_transaccion_paypal`),
    FOREIGN KEY (`id_reserva`) REFERENCES `reservas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. CREAR TABLA DE DISPERSIONES (PAGOS A ANFITRIONES)
CREATE TABLE IF NOT EXISTS `dispersiones` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `id_reserva` INT NOT NULL,
    `id_anfitrion` INT NOT NULL,
    `monto` DECIMAL(10,2) NOT NULL,
    `estado` ENUM('pendiente', 'procesando', 'completado', 'fallido') NOT NULL DEFAULT 'pendiente',
    `metodo_dispersion` VARCHAR(50) NULL COMMENT 'paypal_payout, transferencia_manual',
    `id_payout_paypal` VARCHAR(100) NULL,
    `fecha_programada` DATE NULL,
    `fecha_procesado` DATETIME NULL,
    `notas` TEXT NULL,
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_reserva` (`id_reserva`),
    INDEX `idx_anfitrion` (`id_anfitrion`),
    INDEX `idx_estado` (`estado`),
    FOREIGN KEY (`id_reserva`) REFERENCES `reservas`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_anfitrion`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. CREAR ÍNDICES PARA OPTIMIZACIÓN
CREATE INDEX IF NOT EXISTS `idx_reservas_estado_pago` ON `reservas`(`estado_pago`);
CREATE INDEX IF NOT EXISTS `idx_reservas_fecha_pago` ON `reservas`(`fecha_pago`);

-- 7. INSERTAR CONFIGURACIÓN DE COMISIÓN POR DEFECTO (si no existe)
-- La comisión estándar es 12% del subtotal para la plataforma

-- =====================================================
-- NOTAS DE IMPLEMENTACIÓN:
-- 
-- Split Payment Logic:
-- - monto_total = monto_subtotal + tarifa_limpieza + tarifa_servicio
-- - tarifa_servicio = monto_subtotal * (comision_plataforma / 100)
-- - monto_anfitrion = monto_subtotal + tarifa_limpieza
-- 
-- El 100% del pago entra a la cuenta admin, luego se registra
-- en tabla 'dispersiones' cuánto se debe pagar al anfitrión.
-- =====================================================
