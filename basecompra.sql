-- ============================================================
--  Base de datos: basecompra
--  Proyecto: Compras Sencillas
-- ============================================================

CREATE DATABASE IF NOT EXISTS basecompra
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE basecompra;

-- ------------------------------------------------------------
--  Tabla: producto
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS producto (
  id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(150)    NOT NULL,
  descripcion TEXT,
  precio      DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  stock       INT             NOT NULL DEFAULT 0,
  imagen      LONGTEXT,           -- base64 de la imagen
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  Tabla: pedido
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pedido (
  id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  fecha       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total       DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  Tabla: detalle_pedido
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS detalle_pedido (
  id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  pedido_id   INT UNSIGNED    NOT NULL,
  producto_id INT UNSIGNED    NOT NULL,
  cantidad    INT             NOT NULL DEFAULT 1,
  precio_unit DECIMAL(10,2)  NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (pedido_id)   REFERENCES pedido(id)   ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES producto(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  Datos de ejemplo
-- ------------------------------------------------------------
INSERT INTO producto (nombre, descripcion, precio, stock) VALUES
  ('Laptop HP 15',       'Intel i5, 8GB RAM, 256GB SSD',       12500.00,  8),
  ('Mouse inalámbrico',  'Ergonómico, 1600 DPI',                  350.00, 25),
  ('Teclado mecánico',   'RGB, switches Blue',                    980.00, 12),
  ('Monitor 24"',        'Full HD IPS, 75Hz',                    4200.00,  5),
  ('Audífonos BT',       'Cancelación de ruido activa',          1800.00, 15),
  ('Webcam HD',          '1080p, micrófono integrado',            750.00, 20);
