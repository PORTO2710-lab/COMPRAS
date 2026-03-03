# 🛒 Compras - Full Stack E-commerce App

Una plataforma completa de gestión de ventas y productos, construida con una arquitectura de microservicios y contenedorizada con Docker.

## 🚀 Demo en vivo
Explora la tienda aquí: [https://compras-8z5m.onrender.com/](https://compras-8z5m.onrender.com/)

## ✨ Características Principales
* **Gestión de Catálogo (CRUD):** Añadir, editar y eliminar productos en tiempo real.
* **Carrito de Compras:** Sistema dinámico de selección y suma de productos.
* **Persistencia de Datos:** Integración total con PostgreSQL.
* **Docker Ready:** Entorno de ejecución estandarizado mediante contenedores.
* **Checkout Flow:** Integración fluida hacia servicios de pago externos.

## 🛠️ Stack Tecnológico
* **Backend:** Node.js / Express (u otro framework utilizado).
* **Base de Datos:** PostgreSQL.
* **Contenedorización:** Docker.
* **Herramientas:** PgAdmin para administración de DB.
* **Hosting:** Render.

## 📦 Configuración y Despliegue

### Variables de Entorno Necesarias
Para que el proyecto funcione, debes configurar las siguientes variables en tu entorno de Render o archivo `.env`:
* `DB_HOST`: Host de tu base de datos PostgreSQL.
* `DB_NAME`: Nombre de la base de datos.
* `DB_USER`: Usuario de la base de datos.
* `DB_PASSWORD`: Contraseña de acceso.
* `PORT`: Puerto de escucha del servidor.

### Ejecución con Docker
1. Construye la imagen:
   ```bash
   docker build -t e-commerce-app .
