-- =====================================================
-- MÓDULO ALQUILER VACACIONAL - ESQUEMA DE BASE DE DATOS
-- Integración con tabla existente 'habitaciones'
-- =====================================================

-- Tabla para múltiples fotos por propiedad
CREATE TABLE IF NOT EXISTS fotos_propiedad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_habitacion INT NOT NULL,
    url_imagen VARCHAR(255) NOT NULL,
    es_principal TINYINT(1) DEFAULT 0,
    orden INT DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id) ON DELETE CASCADE,
    INDEX idx_habitacion (id_habitacion),
    INDEX idx_principal (es_principal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de amenidades disponibles
CREATE TABLE IF NOT EXISTS amenidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    icono VARCHAR(50) DEFAULT 'fas fa-check',
    categoria VARCHAR(50) DEFAULT 'general',
    estado TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla pivote: relación propiedad-amenidades
CREATE TABLE IF NOT EXISTS propiedad_amenidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_habitacion INT NOT NULL,
    id_amenidad INT NOT NULL,
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (id_amenidad) REFERENCES amenidades(id) ON DELETE CASCADE,
    UNIQUE KEY unique_propiedad_amenidad (id_habitacion, id_amenidad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de evaluaciones/reseñas
CREATE TABLE IF NOT EXISTS evaluaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_habitacion INT NOT NULL,
    id_usuario INT NOT NULL,
    calificacion_general DECIMAL(2,1) NOT NULL,
    limpieza DECIMAL(2,1) DEFAULT 5.0,
    veracidad DECIMAL(2,1) DEFAULT 5.0,
    llegada DECIMAL(2,1) DEFAULT 5.0,
    comunicacion DECIMAL(2,1) DEFAULT 5.0,
    ubicacion DECIMAL(2,1) DEFAULT 5.0,
    calidad_precio DECIMAL(2,1) DEFAULT 5.0,
    comentario TEXT,
    fecha_evaluacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_habitacion (id_habitacion),
    INDEX idx_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de favoritos de usuarios
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_habitacion INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (id_habitacion, id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar nuevos campos a la tabla habitaciones existente
ALTER TABLE habitaciones 
ADD COLUMN IF NOT EXISTS habitaciones_num INT DEFAULT 1 AFTER capacidad,
ADD COLUMN IF NOT EXISTS camas INT DEFAULT 1 AFTER habitaciones_num,
ADD COLUMN IF NOT EXISTS banos INT DEFAULT 1 AFTER camas,
ADD COLUMN IF NOT EXISTS direccion VARCHAR(255) DEFAULT NULL AFTER banos,
ADD COLUMN IF NOT EXISTS latitud DECIMAL(10, 8) DEFAULT NULL AFTER direccion,
ADD COLUMN IF NOT EXISTS longitud DECIMAL(11, 8) DEFAULT NULL AFTER latitud,
ADD COLUMN IF NOT EXISTS tarifa_limpieza DECIMAL(10,2) DEFAULT 0 AFTER precio,
ADD COLUMN IF NOT EXISTS es_favorito_huespedes TINYINT(1) DEFAULT 0 AFTER estado,
ADD COLUMN IF NOT EXISTS calificacion_promedio DECIMAL(2,1) DEFAULT 0 AFTER es_favorito_huespedes,
ADD COLUMN IF NOT EXISTS total_evaluaciones INT DEFAULT 0 AFTER calificacion_promedio;

-- Insertar amenidades predefinidas
INSERT INTO amenidades (nombre, icono, categoria) VALUES 
('Wifi', 'fas fa-wifi', 'basico'),
('Cocina', 'fas fa-utensils', 'basico'),
('Aire acondicionado', 'fas fa-snowflake', 'climatizacion'),
('Calefacción', 'fas fa-temperature-high', 'climatizacion'),
('Lavadora', 'fas fa-tshirt', 'lavanderia'),
('Secadora', 'fas fa-wind', 'lavanderia'),
('TV', 'fas fa-tv', 'entretenimiento'),
('Estacionamiento gratuito', 'fas fa-parking', 'estacionamiento'),
('Piscina', 'fas fa-swimming-pool', 'exterior'),
('Jacuzzi', 'fas fa-hot-tub', 'exterior'),
('Parrilla/BBQ', 'fas fa-fire', 'exterior'),
('Área de trabajo', 'fas fa-laptop', 'trabajo'),
('Se permiten mascotas', 'fas fa-paw', 'politicas'),
('Se permite fumar', 'fas fa-smoking', 'politicas'),
('Apto para eventos', 'fas fa-glass-cheers', 'politicas'),
('Detector de humo', 'fas fa-bell', 'seguridad'),
('Detector de monóxido', 'fas fa-shield-alt', 'seguridad'),
('Botiquín', 'fas fa-first-aid', 'seguridad'),
('Extintor', 'fas fa-fire-extinguisher', 'seguridad'),
('Cerradura en la puerta', 'fas fa-lock', 'seguridad'),
('Cuna', 'fas fa-baby', 'familia'),
('Silla alta', 'fas fa-chair', 'familia'),
('Secadora de pelo', 'fas fa-wind', 'bano'),
('Plancha', 'fas fa-iron', 'otros'),
('Permite dejar equipaje', 'fas fa-suitcase', 'otros'),
('Entrada independiente', 'fas fa-door-open', 'acceso')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Vista para obtener propiedades con estadísticas
CREATE OR REPLACE VIEW v_propiedades_completas AS
SELECT 
    h.*,
    (SELECT url_imagen FROM fotos_propiedad WHERE id_habitacion = h.id AND es_principal = 1 LIMIT 1) as foto_principal,
    (SELECT COUNT(*) FROM fotos_propiedad WHERE id_habitacion = h.id) as total_fotos,
    (SELECT AVG(calificacion_general) FROM evaluaciones WHERE id_habitacion = h.id AND estado = 1) as rating,
    (SELECT COUNT(*) FROM evaluaciones WHERE id_habitacion = h.id AND estado = 1) as num_evaluaciones
FROM habitaciones h
WHERE h.estado = 1;
