-- Crear base de datos
CREATE DATABASE IF NOT EXISTS finca_cafetera;
USE finca_cafetera;

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    rol ENUM('admin', 'gerente', 'supervisor', 'empleado') DEFAULT 'empleado',
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'PAS') DEFAULT 'CC',
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    cargo VARCHAR(50),
    salario_base DECIMAL(10,2) DEFAULT 0,
    estado ENUM('activo', 'inactivo', 'vacaciones', 'licencia') DEFAULT 'activo',
    fecha_contratacion DATE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_entidad ENUM('persona', 'empresa') NOT NULL,
    tipo_identificacion ENUM('CC', 'CE', 'PAS', 'NIT') DEFAULT 'CC',
    numero_identificacion VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(100),
    apellidos VARCHAR(100),
    razon_social VARCHAR(200),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    municipio VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de proveedores
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nit VARCHAR(20) UNIQUE NOT NULL,
    razon_social VARCHAR(200) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    contacto_nombre VARCHAR(100),
    contacto_telefono VARCHAR(20),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de insumos
CREATE TABLE insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    categoria ENUM('fertilizante', 'pesticida', 'herramienta', 'semilla', 'otro') DEFAULT 'otro',
    unidad_medida VARCHAR(20) NOT NULL,
    stock_actual DECIMAL(10,2) DEFAULT 0,
    stock_minimo DECIMAL(10,2) DEFAULT 0,
    precio_unitario DECIMAL(10,2) DEFAULT 0,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de actividades
CREATE TABLE actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo ENUM('agricola', 'mantenimiento', 'administrativa', 'cosecha', 'procesamiento') DEFAULT 'agricola',
    duracion_estimada INT,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de lotes/cosechas
CREATE TABLE lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_lote VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    area DECIMAL(8,2) NOT NULL,
    variedad_cafe VARCHAR(50),
    fecha_siembra DATE,
    estado ENUM('activo', 'inactivo', 'cosechado') DEFAULT 'activo',
    observaciones TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de cosechas
CREATE TABLE cosechas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,
    fecha_cosecha DATE NOT NULL,
    kilos_cosechados DECIMAL(8,2) NOT NULL,
    calidad ENUM('premium', 'especial', 'estandar', 'comercial') DEFAULT 'estandar',
    rendimiento DECIMAL(8,2),
    observaciones TEXT,
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lote_id) REFERENCES lotes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de subproductos
CREATE TABLE subproductos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cosecha_id INT NOT NULL,
    tipo ENUM('pulpa', 'cascara', 'broza') NOT NULL,
    cantidad DECIMAL(8,2) NOT NULL,
    unidad_medida VARCHAR(20) DEFAULT 'kg',
    destino ENUM('venta', 'compost', 'desecho', 'almacenado') DEFAULT 'almacenado',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cosecha_id) REFERENCES cosechas(id)
);

-- Tabla de ventas
CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha_venta DATE NOT NULL,
    kilos_vendidos DECIMAL(8,2) NOT NULL,
    precio_kilo DECIMAL(8,2) NOT NULL,
    calidad ENUM('premium', 'especial', 'estandar', 'comercial') DEFAULT 'estandar',
    total_venta DECIMAL(10,2) NOT NULL,
    forma_pago ENUM('efectivo', 'transferencia', 'credito') DEFAULT 'efectivo',
    estado ENUM('pendiente', 'pagada', 'cancelada') DEFAULT 'pendiente',
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de ingresos
CREATE TABLE ingresos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('venta_cafe', 'subproductos', 'alquiler', 'otros') NOT NULL,
    fecha_ingreso DATE NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    cliente_id INT,
    venta_id INT,
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (venta_id) REFERENCES ventas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de egresos
CREATE TABLE egresos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('insumos', 'salarios', 'mantenimiento', 'servicios', 'otros') NOT NULL,
    fecha_egreso DATE NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    proveedor_id INT,
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de jornales
CREATE TABLE jornales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    fecha_jornal DATE NOT NULL,
    horas_trabajadas DECIMAL(4,2) NOT NULL,
    tarifa_hora DECIMAL(8,2) NOT NULL,
    actividad_id INT NOT NULL,
    cosecha_id INT,
    total_pago DECIMAL(8,2) NOT NULL,
    estado ENUM('pendiente', 'pagado') DEFAULT 'pendiente',
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id),
    FOREIGN KEY (actividad_id) REFERENCES actividades(id),
    FOREIGN KEY (cosecha_id) REFERENCES cosechas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de asignación de actividades a empleados
CREATE TABLE asignaciones_actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    actividad_id INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    fecha_limite DATE,
    estado ENUM('pendiente', 'en_progreso', 'completada', 'cancelada') DEFAULT 'pendiente',
    observaciones TEXT,
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id),
    FOREIGN KEY (actividad_id) REFERENCES actividades(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de consumo de insumos en actividades
CREATE TABLE consumo_insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actividad_id INT NOT NULL,
    insumo_id INT NOT NULL,
    cantidad DECIMAL(8,2) NOT NULL,
    fecha_consumo DATE NOT NULL,
    usuario_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (actividad_id) REFERENCES actividades(id),
    FOREIGN KEY (insumo_id) REFERENCES insumos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Insertar datos iniciales
INSERT INTO usuarios (username, password_hash, nombre_completo, email, rol) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Principal', 'admin@finca.com', 'admin'),
('gerente', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gerente de Finca', 'gerente@finca.com', 'gerente');

INSERT INTO actividades (nombre, descripcion, tipo) VALUES
('Siembra', 'Plantación de nuevas matas de café', 'agricola'),
('Fertilización', 'Aplicación de fertilizantes', 'agricola'),
('Cosecha', 'Recolección de granos de café maduros', 'cosecha'),
('Poda', 'Mantenimiento y poda de plantas', 'mantenimiento'),
('Limpieza', 'Limpieza de áreas de cultivo', 'mantenimiento');

INSERT INTO lotes (codigo_lote, nombre, area, variedad_cafe, fecha_siembra) VALUES
('LOTE-A1', 'Lote Principal Norte', 2.5, 'Caturra', '2022-03-15'),
('LOTE-B2', 'Lote Secundario Sur', 1.8, 'Castillo', '2022-06-20');

INSERT INTO empleados (documento_identidad, tipo_documento, nombres, apellidos, telefono, cargo, salario_base, estado, fecha_contratacion) VALUES
('123456789', 'CC', 'Juan', 'Pérez', '3001234567', 'Cosechero', 1200000, 'activo', '2023-01-15'),
('987654321', 'CC', 'María', 'Gómez', '3007654321', 'Supervisora', 1800000, 'activo', '2023-02-20');

INSERT INTO clientes (tipo_entidad, tipo_identificacion, numero_identificacion, nombres, apellidos, telefono, email) VALUES
('persona', 'CC', '111222333', 'Carlos', 'Rodríguez', '3014445566', 'carlos@email.com'),
('empresa', 'NIT', '900123456-7', 'Café Premium S.A.', NULL, '6012345678', 'ventas@cafepremium.com');

INSERT INTO insumos (nombre, descripcion, categoria, unidad_medida, stock_actual, stock_minimo, precio_unitario) VALUES
('Fertilizante NPK', 'Fertilizante completo', 'fertilizante', 'kg', 500, 100, 2500),
('Insecticida Orgánico', 'Control de plagas', 'pesticida', 'lt', 50, 10, 15000);