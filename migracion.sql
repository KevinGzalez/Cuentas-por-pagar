-- Crear la base de datos
CREATE DATABASE cuentasporpagar;

-- Usar la base de datos
USE cuentasporpagar;

-- Crear la tabla 'asientos_contables'
CREATE TABLE asientos_contables (
    IDentificiadorasiento INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    identificador_tipo_inventario INT,
    cuenta_contable VARCHAR(50),
    tipo_de_movimiento ENUM('DB', 'CR') NOT NULL,
    fecha_asiento DATE NOT NULL,
    monto_asiento DECIMAL(15, 2) NOT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL
);

-- Crear la tabla 'concepto_de_pagos'
CREATE TABLE concepto_de_pagos (
    identificador INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    estado ENUM('Activo', 'Inactivo') NOT NULL
);

-- Crear la tabla 'Proveedores'
CREATE TABLE Proveedores (
    identificador INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    tipo_de_persona ENUM('Fisica', 'Juridica') NOT NULL,
    cedula_rnc VARCHAR(20) NOT NULL,
    balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    estado ENUM('Activo', 'Inactivo') NOT NULL
);

-- Crear la tabla 'entrada_de_documentos'
CREATE TABLE entrada_de_documentos (
    numero_documento INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura_a_pagar INT NOT NULL,
    fecha_document DATE NOT NULL,
    monto DECIMAL(15, 2) NOT NULL,
    fecha_registro DATE NOT NULL,
    proveedor INT,
    estado ENUM('Pendiente', 'Pagado') NOT NULL,
    FOREIGN KEY (proveedor) REFERENCES Proveedores(identificador)
);

-- Insertar registros en la tabla 'concepto_de_pagos'
INSERT INTO concepto_de_pagos (descripcion, estado) VALUES
('Pago de servicios públicos', 'Activo'),
('Pago de proveedores', 'Activo'),
('Pago de impuestos', 'Activo'),
('Pago de alquiler', 'Activo');

-- Insertar registros en la tabla 'Proveedores'
INSERT INTO Proveedores (nombre, tipo_de_persona, cedula_rnc, balance, estado) VALUES
('Distribuidora La Nacional', 'Juridica', '1-01-12345-3', 50000.00, 'Activo'),
('Juan Pérez', 'Fisica', '402-1234567-8', 10000.50, 'Activo'),
('Suministros Tecnológicos SRL', 'Juridica', '1-02-54321-9', 75000.75, 'Activo'),
('María González', 'Fisica', '402-9876543-2', 2000.00, 'Inactivo');

-- Insertar registros en la tabla 'entrada_de_documentos'
INSERT INTO entrada_de_documentos (numero_factura_a_pagar, fecha_document, monto, fecha_registro, proveedor, estado) VALUES
(1001, '2025-03-05', 15000.00, '2025-03-06', 1, 'Pendiente'),
(1002, '2025-03-04', 7500.50, '2025-03-05', 2, 'Pagado'),
(1003, '2025-03-03', 30000.75, '2025-03-04', 3, 'Pendiente'),
(1004, '2025-03-02', 5000.00, '2025-03-03', 4, 'Pagado');

create table Usuarios(
ID int auto_increment primary key,
Nombre varchar(50), 
Contrasena varchar(50),
rol enum('usuario', 'admin'));
