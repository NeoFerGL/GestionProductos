# 🔌 Sistema de Gestión de Productos con Servicios SOAP y Firebase

## 📚 Descripción del Proyecto
Este proyecto implementa un sistema que permite la gestión y consulta de productos a través de servicios web SOAP. Está compuesto por:

- Un **servicio backend en C# (WCF)** que permite operaciones CRUD sobre una base de datos en Firebase.
- Un **cliente SOAP en PHP** que realiza consultas específicas para usuarios con rol de ventas.
- Una **interfaz de pruebas en SOAP UI** que facilita la validación de las operaciones.

## 🔧 Tecnologías Clave
- **Lenguajes**: C# (.NET Framework), PHP 7+
- **Base de Datos**: Firebase Realtime Database
- **Protocolo**: SOAP (Simple Object Access Protocol)
- **Servicios Web**: WCF (Windows Communication Foundation), cliente SOAP PHP
- **Herramientas de prueba**: SOAP UI
- **Librerías**:
  - FireSharp (para acceso a Firebase en C#)
  - MyFirebase.php (para acceso a Firebase desde PHP)
  - Extensiones SOAP en PHP

## ⚙️ Funcionalidades Principales

### 🧩 Backend en C# - `WSPersonas.svc.cs`
- **Rol**: Almacén
- **Operaciones disponibles**:
  - `setProd`: Crea un nuevo producto
  - `updateProd`: Actualiza un producto existente
  - `deleteProd`: Elimina un producto por ISBN
- **Características**:
  - Validación de credenciales con MD5
  - Estructura clara de respuesta (`code`, `message`, `status`, `data`)
  - Conexión segura a Firebase usando FireSharp

### 🌐 Cliente en PHP - `productoV3.php`
- **Rol**: Ventas
- **Operaciones disponibles**:
  - `getProd`: Lista productos por categoría
  - `getDetails`: Devuelve los detalles de un producto por ISBN
- **Características**:
  - Conexión a Firebase usando `MyFirebase.php`
  - Validación con MD5
  - Respuestas formateadas en JSON legible (usando `JSON_PRETTY_PRINT`)

### 🧪 Interfaz de Pruebas - SOAP UI
- Permite probar todas las operaciones mediante solicitudes XML.
- Visualización clara de respuestas y códigos de estado.
- Uso de endpoints tanto del backend C# como del cliente PHP.

## 🎯 Beneficios Clave
- **Interoperabilidad**: Comunicación entre tecnologías distintas (PHP y C#) a través de SOAP.
- **Fácil de probar**: Toda la lógica es accesible mediante SOAP UI.
- **Compatibilidad multiplataforma**: Uso de Firebase como backend común.

## 🖼️ Capturas del Sistema

### Metodos de C#
![Funcionalidad de metodo de C#](https://github.com/user-attachments/assets/0b72d864-5672-4e1a-bbee-5996c06af077)

### Metodos de PHP
![Funcionalidad de metodo d PHP](https://github.com/user-attachments/assets/bd2bad22-c8ac-4b09-8a51-22c18214943f)

### Producto en la BD
![Producto en firebase](https://github.com/user-attachments/assets/b62065aa-df91-4546-b42e-c97ddf46fd16)

## 👥 Desarollado
- **Fernando Garza De La Luz**

## 📝 Notas Finales
- **Verifica que los servicios estén activos**:
  - `1.`: Que Xampp este activo con Apache para PHP y en la carpeta htdocs y en la ruta adecuada ejemplo http://localhost/gestionProductos/productoV3.php?wsdl
  - `2.`: Que Visual studio 2022 tamabien este ejecutandose en http://localhost:54793/WSPersonas.svc?wsdl
  - `3.`: Usar SoapUI y cargar las wsdl para usar los metodos.
  - `4.`: Credencial en c# AuthSecret = "kMqScyNtxMSk5MCXieq9nWa5Up7oHmHozj544rpI" y BasePath = "https://productosws-35823-default-rtdb.firebaseio.com/"
  - `5.`: En php $firebase = new MyFirebase('productosws-35823-default-rtdb');
