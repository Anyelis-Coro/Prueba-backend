# API Catálogo de Productos y Precios Multidivisa

API RESTful desarrollada con **Laravel 12**, **PostgreSQL** y **Laravel Sanctum** para la gestión integral de un catálogo de productos con desglose de costos financieros y soporte para precios en múltiples divisas.

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Arquitectura y Estructura del Código](#-arquitectura-y-estructura-del-código)
- [Medidas de Seguridad](#-medidas-de-seguridad)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación y Despliegue](#-instalación-y-despliegue)
- [Credenciales de Prueba](#-credenciales-de-prueba)
- [Colección de Postman](#-colección-de-postman)
- [Especificación de Endpoints](#-especificación-de-endpoints)
  - [Autenticación](#1-autenticación)
  - [Productos (Catálogo Base)](#2-productos-catálogo-base)
  - [Precios Multidivisa](#3-precios-multidivisa)
- [Códigos de Respuesta HTTP](#-códigos-de-respuesta-http)

---

## 🚀 Características Principales

- **Gestión de Catálogo:** Registro, consulta paginada, detalle, actualización y eliminación de productos.
- **Desglose Financiero:** Cada producto registra su precio de venta, costo de fabricación (`manufacturing_cost`) y costo de impuestos (`tax_cost`), vinculado a una divisa base.
- **Precios Multidivisa:** Posibilidad de asignar precios alternativos en diferentes divisas a un producto, con validación de unicidad (evita duplicados de divisa por producto).
- **Transformación de Respuestas:** Uso de *API Resources* para garantizar respuestas desacopladas de la base de datos, con tipos numéricos estrictos y carga eficiente de relaciones (`eager loading`).
- **Autenticación Stateless:** Protección de rutas mediante tokens de acceso personal con *Laravel Sanctum*.

---

## 🏗 Arquitectura y Estructura del Código

El proyecto sigue las mejores prácticas y estándares de la arquitectura MVC de Laravel:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Inicio de sesión y expedición de tokens Sanctum
│   │   ├── ProductController.php       # CRUD completo de productos con paginación
│   │   └── ProductPriceController.php  # Consulta y registro de precios multidivisa
│   ├── Requests/
│   │   ├── StoreProductRequest.php      # Validaciones y tipos para creación de producto
│   │   ├── UpdateProductRequest.php     # Validaciones parciales (sometimes) para edición
│   │   └── StoreProductPriceRequest.php # Validación de divisa existente y monto
│   └── Resources/
│       ├── ProductResource.php          # Formateador JSON del producto con su divisa
│       └── ProductPriceResource.php     # Formateador JSON de los precios asociados
└── Models/
    ├── Currency.php                    # Divisas disponibles y tasas
    ├── Product.php                     # Modelo de Producto con casts decimales y relaciones
    ├── ProductPrice.php                # Tabla intermedia de precios multidivisa
    └── User.php                        # Usuarios del sistema con HasApiTokens
```

---

## 🔒 Medidas de Seguridad

1. **Autenticación basada en Tokens (Sanctum):** Las rutas de escritura (`POST`, `PUT`, `PATCH`, `DELETE`) están estrictamente restringidas bajo el middleware `auth:sanctum`.
2. **Validación Exhaustiva de Entradas:** Todas las operaciones de entrada están encapsuladas en `Form Requests` dedicados, evitando inyecciones de datos no permitidos o valores fuera de rango (como precios negativos).
3. **Protección contra Asignación Masiva (*Mass Assignment*):** Todos los modelos implementan la propiedad `$fillable` de manera explícita.
4. **Validación de Integridad Relacional:** Las divisas asociadas se validan a nivel de base de datos con reglas `exists:currencies,id` y restricciones de clave foránea con integridad referencial.
5. **Control de Duplicidad:** Validación de lógica de negocio que impide asignar dos veces la misma divisa al mismo producto.
6. **Casting de Tipos y Ocultamiento de Datos Sensibles:** Castings estrictos a nivel de modelo y exclusión de hashes y tokens en las respuestas JSON.

---

## 🛠 Requisitos Previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y en ejecución.
- [Git](https://git-scm.com/) (opcional, para clonar el repositorio).

---

## ⚙️ Instalación y Despliegue

La aplicación se encuentra completamente dockerizada mediante **Laravel Sail** (PHP 8.5 + PostgreSQL 18).

### 1. Clonar el repositorio y acceder a la carpeta
```bash
git clone <URL_DEL_REPOSITORIO>
cd prueba_tecnica_anyelis
```

### 2. Configurar el archivo de entorno
Si el archivo `.env` no existe, créalo a partir del ejemplo:
```bash
cp .env.example .env
```
*(Las credenciales de PostgreSQL en el `.env` ya se encuentran preconfiguradas para funcionar directamente con el servicio de Sail).*

### 3. Iniciar los contenedores Docker
Ejecuta el siguiente comando para levantar el entorno:
```bash
./vendor/bin/sail up -d
```
> **Nota para Windows (PowerShell / CMD):**  
> Si no tienes el alias `sail` configurado, puedes ejecutar:  
> `docker compose up -d`

### 4. Generar la clave de la aplicación (si no está generada)
```bash
./vendor/bin/sail artisan key:generate
```

### 5. Ejecutar migraciones y datos semilla (Seeders)
Este paso crea las tablas requeridas, las divisas base (USD, EUR, VES) y el usuario de prueba para autenticación:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

La API estará lista y accesible en: `http://localhost/api`

---

## 🔑 Credenciales de Prueba

Para consumir las rutas protegidas, utiliza las siguientes credenciales generadas por el seeder:

| Parámetro | Valor |
| :--- | :--- |
| **Email** | `test@example.com` |
| **Password** | `password` |

---

## 📬 Colección de Postman

Se incluye en la raíz del proyecto el archivo listo para importar:
📁 **`postman_collection.json`**

### ¿Cómo utilizarla?
1. Abre **Postman** y haz clic en **Import**.
2. Selecciona el archivo `postman_collection.json` ubicado en la carpeta del proyecto.
3. La colección incluye la variable `{{base_url}}` (por defecto `http://localhost`).
4. Ejecuta primero la petición de la carpeta **01. Autenticación -> Login**.
5. **Autoguardado de Token:** La petición de login cuenta con un script de prueba que guarda automáticamente el Bearer Token en la variable `{{bearer_token}}` de la colección, permitiendo ejecutar el resto de las peticiones protegidas sin configuración manual adicional.

---

## 📖 Especificación de Endpoints

### 1. Autenticación

#### • Iniciar Sesión (Obtener Bearer Token)
* **Método:** `POST`
* **Ruta:** `/api/login`
* **Acceso:** Público
* **Cuerpo de la petición (JSON):**
  ```json
  {
    "email": "test@example.com",
    "password": "password"
  }
  ```
* **Respuesta exitosa (`200 OK`):**
  ```json
  {
    "token": "1|1mWQ9lYCyVWRl5rCx4JD1HucS07t6EdHd5L3NTAw3f434c11",
    "user": {
      "id": 1,
      "name": "Usuario de Prueba",
      "email": "test@example.com"
    }
  }
  ```

---

### 2. Productos (Catálogo Base)

#### • Listar Productos
* **Método:** `GET`
* **Ruta:** `/api/products`
* **Acceso:** Público
* **Parámetros URL (Opcional):** `?page=1`
* **Respuesta exitosa (`200 OK`):** Retorna lista paginada de productos con su divisa asociada (`data`, `links`, `meta`).

#### • Consultar Detalle de un Producto
* **Método:** `GET`
* **Ruta:** `/api/products/{id}`
* **Acceso:** Público
* **Respuesta exitosa (`200 OK`):**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Laptop Profesional 16\"",
      "description": "Portátil de alta gama para desarrollo",
      "price": 1250.00,
      "tax_cost": 200.00,
      "manufacturing_cost": 850.00,
      "currency": {
        "id": 1,
        "name": "US Dollar",
        "symbol": "$"
      },
      "created_at": "2026-10-28 18:00:00",
      "updated_at": "2026-10-28 18:00:00"
    }
  }
  ```

#### • Crear un Producto
* **Método:** `POST`
* **Ruta:** `/api/products`
* **Acceso:** **Protegido** (`Bearer Token`)
* **Headers requeridos:**
  * `Authorization: Bearer <TOKEN>`
  * `Accept: application/json`
  * `Content-Type: application/json`
* **Cuerpo de la petición (JSON):**
  ```json
  {
    "name": "Laptop Profesional 16\"",
    "description": "Portátil de alta gama para desarrollo",
    "price": 1250.00,
    "currency_id": 1,
    "tax_cost": 200.00,
    "manufacturing_cost": 850.00
  }
  ```
* **Respuesta exitosa (`201 Created`):** Retorna el objeto del producto creado.

#### • Actualizar un Producto
* **Método:** `PUT` / `PATCH`
* **Ruta:** `/api/products/{id}`
* **Acceso:** **Protegido** (`Bearer Token`)
* **Cuerpo de la petición (JSON):** *(Permite actualizar campos individuales o completos)*
  ```json
  {
    "name": "Laptop Profesional 16\" - Edición 2026",
    "price": 1199.99
  }
  ```
* **Respuesta exitosa (`200 OK`):** Retorna el producto con los datos actualizados.

#### • Eliminar un Producto
* **Método:** `DELETE`
* **Ruta:** `/api/products/{id}`
* **Acceso:** **Protegido** (`Bearer Token`)
* **Respuesta exitosa (`204 No Content`):** Sin contenido.

---

### 3. Precios Multidivisa

#### • Listar Precios de un Producto
* **Método:** `GET`
* **Ruta:** `/api/products/{id}/prices`
* **Acceso:** Público
* **Respuesta exitosa (`200 OK`):**
  ```json
  {
    "data": [
      {
        "id": 1,
        "price": 1150.00,
        "currency": {
          "id": 2,
          "name": "Euro",
          "symbol": "€"
        },
        "created_at": "2026-10-28 18:30:00",
        "updated_at": "2026-10-28 18:30:00"
      }
    ]
  }
  ```

#### • Registrar Precio en Otra Divisa
* **Método:** `POST`
* **Ruta:** `/api/products/{id}/prices`
* **Acceso:** **Protegido** (`Bearer Token`)
* **Cuerpo de la petición (JSON):**
  ```json
  {
    "currency_id": 2,
    "price": 1150.00
  }
  ```
* **Respuesta exitosa (`201 Created`):** Retorna el nuevo registro de precio con su divisa asociada.
* **Control de duplicados (`422 Unprocessable Content`):** Si la divisa ya fue asignada al producto, responderá:
  ```json
  {
    "message": "Este producto ya tiene un precio registrado en esta divisa."
  }
  ```

---

## 🚦 Códigos de Respuesta HTTP

| Código | Significado | Escenario |
| :---: | :--- | :--- |
| **`200 OK`** | Petición exitosa | Consultas (`GET`) y actualizaciones (`PUT`/`PATCH`). |
| **`201 Created`** | Recurso creado | Creación de productos o precios (`POST`). |
| **`204 No Content`** | Petición exitosa sin cuerpo | Eliminación exitosa de un producto (`DELETE`). |
| **`401 Unauthorized`** | No autorizado | Falta el token de autenticación o el token es inválido. |
| **`404 Not Found`** | No encontrado | El producto o recurso especificado en la URL no existe. |
| **`422 Unprocessable Content`** | Error de validación / Regla de negocio | Faltan campos obligatorios, tipos erróneos o divisa repetida. |
