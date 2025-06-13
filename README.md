# Sistema de Gestión de Almacén

## 📋 Descripción del Proyecto

Sistema de Gestión de Almacén desarrollado para la administración eficiente de inventario, entradas y salidas de productos. Esta aplicación permite el registro, control y seguimiento de todos los movimientos de productos en el almacén, generando reportes detallados para la toma de decisiones.

### ✨ Características principales

-   📦 **Gestión de productos** - Registro y administración de productos por categorías
-   📊 **Control de inventario** - Seguimiento de stock en tiempo real por lotes
-   🏢 **Administración de proveedores** - Registro de proveedores para ingresos de productos
-   🏬 **Gestión de unidades** - Control de las unidades que solicitan productos
-   📥 **Ingresos de productos** - Registro de entradas con detalles por lote y costo
-   📤 **Salidas de productos** - Registro de salidas con método FIFO (primero en entrar, primero en salir)
-   📝 **Reportes** - Generación de reportes de saldos y movimientos en PDF
-   📈 **Panel de control** - Dashboard con estadísticas y métricas clave

## 🔧 Requisitos del Sistema

-   PHP >= 8.0
-   Composer
-   MySQL >= 5.7
-   Node.js y NPM
-   Extensiones PHP:
    -   BCMath
    -   Ctype
    -   Fileinfo
    -   JSON
    -   Mbstring
    -   OpenSSL
    -   PDO
    -   Tokenizer
    -   XML

## 🚀 Instalación en Entorno de Desarrollo

Siga estos pasos para instalar y configurar el proyecto en un entorno de desarrollo local:

1. Clone el repositorio:

```bash
git clone [url-del-repositorio]
cd almacen
```

2. Instale las dependencias de PHP con Composer:

```bash
composer install
```

3. Instale las dependencias de JavaScript:

```bash
npm install
```

4. Compile los assets:

```bash
npm run build
```

5. Copie el archivo de configuración y configure sus variables de entorno:

```bash
cp .env.example .env
```

6. Genere una clave de aplicación:

```bash
php artisan key:generate
```

7. Configure la conexión a la base de datos en el archivo `.env`

8. **Opción A**: Ejecute las migraciones y seeders:

```bash
php artisan migrate --seed
```

> El usuario creado por defecto con el seeder es:
>
> -   **Usuario:** `admin`
> -   **Contraseña:** `12345678`

9. **Opción B**: Importe la base de datos desde los archivos SQL:

```bash
# Crear la base de datos primero
mysql -u [usuario] -p -e "CREATE DATABASE IF NOT EXISTS [nombre_base_datos];"

# Importar el esquema principal
mysql -u [usuario] -p [nombre_base_datos] < database/sql/sistema_inventario.sql

# Importar los índices adicionales
mysql -u [usuario] -p [nombre_base_datos] < database/sql/indices_sistema_inventario.sql
```

10. Inicie el servidor de desarrollo:

```bash
php artisan serve
```

11. Acceda a la aplicación en: `http://localhost:8000`

## 🌐 Despliegue en Producción

Para desplegar en un entorno de producción, siga estos pasos adicionales:

1. Configure su servidor web (Apache/Nginx) apuntando al directorio `public/` como raíz del sitio.

2. Asegúrese de que los siguientes directorios tengan permisos de escritura:

    - `storage/`
    - `bootstrap/cache/`

3. Configure el archivo `.env` para producción:

    - Establezca `APP_ENV=production`
    - Establezca `APP_DEBUG=false`
    - Configure una `APP_KEY` segura
    - Configure las credenciales de base de datos

4. Optimice la aplicación para producción:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. Configure un programador de tareas (Cron) si es necesario:

```
* * * * * cd /ruta-a-su-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Estructura de la Base de Datos

El sistema utiliza las siguientes tablas principales:

| Tabla                   | Descripción                       |
| ----------------------- | --------------------------------- |
| 👤 **users**            | Usuarios del sistema              |
| 📑 **categorias**       | Categorías de productos           |
| 📦 **productos**        | Productos en inventario           |
| 🏢 **proveedores**      | Proveedores de productos          |
| 🏬 **unidades**         | Unidades que solicitan productos  |
| 📥 **ingresos**         | Registro de entradas de productos |
| 📄 **detalle_ingresos** | Detalle de productos ingresados   |
| 📤 **salidas**          | Registro de salidas de productos  |
| 📄 **detalle_salidas**  | Detalle de productos retirados    |

## 🖥️ Uso del Sistema

1. **Inicio de sesión** - Acceda con sus credenciales
2. **Dashboard** - Visualice estadísticas generales
3. **Gestión de productos** - Administre el catálogo de productos
4. **Registro de ingresos** - Registre nuevas entradas de productos
5. **Registro de salidas** - Registre las salidas de productos
6. **Reportes** - Genere informes de saldos y movimientos

## 🛠️ Mantenimiento

-   💾 Realice respaldos regulares de la base de datos
-   📋 Verifique y limpie los archivos de logs periódicamente:
    ```bash
    php artisan log:clear
    ```
-   🔄 Actualice regularmente las dependencias:
    ```bash
    composer update
    npm update
    ```

## 📄 Licencia

Este proyecto está licenciado bajo [LICENCIA MIT](https://choosealicense.com/licenses/mit/)
