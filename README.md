# 🎯 Sistema de Atención y Resolución de Clientes (SARC)

**SARC** es un sistema web desarrollado en PHP para la gestión eficiente de tickets de soporte al cliente. Permite la creación, asignación automática, seguimiento y resolución de solicitudes de manera organizada y escalable.

## 📋 Características Principales

- ✅ **Gestión de Tickets**: Creación, asignación y seguimiento completo
- ✅ **Asignación Automática**: Los tickets se asignan automáticamente a agentes disponibles
- ✅ **Sistema de Roles**: Cliente, Agente, Administrador
- ✅ **Dashboard Interactivo**: Visualización en tiempo real del estado de tickets
- ✅ **Escalamiento**: Posibilidad de escalar tickets a diferentes áreas
- ✅ **Bitácora**: Historial completo de acciones y cambios
- ✅ **Reportes**: Generación de reportes detallados

---

## 🛠️ Pre-requisitos

Antes de instalar el proyecto, asegúrate de tener los siguientes componentes:

### 📦 Software Requerido

| Componente | Versión Mínima | Descripción |
|------------|---------------|-------------|
| **PHP** | 8.1+ | Lenguaje de programación principal |
| **Apache** | 2.4+ | Servidor web |
| **MySQL** | 8.0+ | Sistema de gestión de base de datos |
| **Composer** | 2.0+ | Gestor de dependencias PHP |

### 🎁 Instalación Rápida con XAMPP

La forma más sencilla es usar **XAMPP** que incluye todo lo necesario:

1. **Descargar XAMPP**: Ve a [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. **Instalar XAMPP** en tu sistema Windows
3. **Instalar Composer**: [https://getcomposer.org/](https://getcomposer.org/)
   
   > ⚠️ **IMPORTANTE**: Instala Composer **DESPUÉS** de XAMPP para evitar conflictos de PATH

---

## 🌐 Configuración de Host Virtual

Para que el sitio funcione correctamente, es necesario configurar un host virtual en Apache.

### 📝 Paso 1: Configurar Apache Virtual Host

1. **Localizar el archivo de configuración**:
   ```
   P:\XAMPP\apache\conf\extra\httpd-vhosts.conf
   ```
   > 📍 La ruta puede variar según tu instalación de XAMPP

2. **Agregar la configuración del Virtual Host**:
   ```apache
   <VirtualHost *:80>
       ServerAdmin admin@luzelfaro.com
       DocumentRoot "P:/XAMPP/htdocs/Proyectos-php/sarc_project/public"
       ServerName luzelfaro.com
       ErrorLog "logs/luzelfaro.com-error.log"
       CustomLog "logs/luzelfaro.com-access.log" common
   </VirtualHost>
   ```
   
   > ⚠️ **NOTA IMPORTANTE**: 
   > - Ajusta la ruta `DocumentRoot` según tu instalación
   > - La ruta DEBE apuntar a la carpeta `public` del proyecto
   > - El ServerName debe ser exactamente `luzelfaro.com`

### 🖥️ Paso 2: Configurar el archivo Hosts del sistema

1. **Abrir terminal como Administrador**:
   - Presiona `Win + R`
   - Escribe `cmd` y presiona `Ctrl + Shift + Enter`

2. **Navegar a la carpeta del archivo hosts**:
   ```cmd
   cd C:\Windows\System32\drivers\etc
   ```

3. **Editar el archivo hosts**:
   ```cmd
   notepad hosts
   ```

4. **Agregar la siguiente línea** al final del archivo:
   ```
   127.0.0.1       luzelfaro.com
   ```
   
   > 📌 Agregar esta línea sin el símbolo `#` al inicio

5. **Reiniciar el sistema** para que los cambios tomen efecto

---

## 🗃️ Conexión a la Base de Datos

El proyecto utiliza variables de entorno (`.env`) para una configuración flexible y segura.

### 📋 Configuración del archivo .env

1. **Localizar el archivo de ejemplo**:
   En la raíz del proyecto encontrarás `.env.example`

2. **Crear tu archivo de configuración**:
   ```bash
   # Copia el archivo de ejemplo
   copy .env.example .env
   ```

3. **Configurar tus datos de conexión**:
   ```env
   SERVER=localhost
   DBNAME=sarc_database
   USER=tu_usuario_mysql
   PASSWORD=tu_contraseña_mysql
   ```

### 🏗️ Creación de la Base de Datos

#### Paso 1: Crear las tablas y estructuras
1. **Abrir phpMyAdmin** (http://localhost/phpmyadmin)
2. **Crear nueva base de datos** con el nombre que configuraste en `.env`
3. **Ejecutar el script de tablas**:
   - Abre el archivo `scripts_tables.sql` del proyecto
   - Copia y pega el contenido en la consola SQL
   - Ejecuta el script

#### Paso 2: Insertar datos de prueba
1. **Ejecutar el dataset**:
   - Abre el archivo `dataset.sql` del proyecto  
   - Copia y pega el contenido en la consola SQL
   - Ejecuta el script

> 💡 **TIP**: Revisa los comentarios en `dataset.sql` para conocer las contraseñas de los usuarios de prueba

---

## 📁 Estructura del Proyecto

```
sarc_project/
├── 📂 App/
│   ├── 📂 Config/          # Configuraciones (Base de datos, etc.)
│   ├── 📂 Core/            # Núcleo del framework (Router, Controller base)
│   ├── 📂 Helpers/         # Funciones auxiliares
│   ├── 📂 Models/          # Modelos de datos y enums
│   └── 📂 Modules/         # Módulos de la aplicación
│       ├── 📂 Auth/        # Autenticación y usuarios
│       ├── 📂 Dashboard/   # Panel de administración
│       ├── 📂 Tickets/     # Gestión de tickets
│       └── 📂 Reports/     # Reportes y estadísticas
├── 📂 public/              # Punto de entrada web
│   ├── 📄 index.php        # Archivo principal
│   └── 📂 assets/          # CSS, JS, imágenes
├── 📂 resources/
│   └── 📂 views/           # Plantillas y vistas
├── 📂 vendor/              # Dependencias de Composer
├── 📄 .env.example         # Plantilla de variables de entorno
├── 📄 composer.json        # Dependencias del proyecto
├── 📄 scripts_tables.sql   # Script de creación de BD
└── 📄 dataset.sql          # Datos de prueba
```

---

## 📦 Dependencias del Proyecto

### Instalación de Dependencias

Una vez clonado el proyecto, instala las dependencias con Composer:

```bash
# Navegar al directorio del proyecto
cd P:\XAMPP\htdocs\Proyectos-php\sarc_project

# Instalar dependencias
composer install
```

### 📋 Dependencias Principales

| Paquete | Versión | Descripción |
|---------|---------|-------------|
| `vlucas/phpdotenv` | ^5.0 | Gestión de variables de entorno |
| `tecnickcom/tcpdf` | ^6.0 | Generación de reportes PDF |

---

## 🚀 Arrancar el Proyecto

### ✅ Lista de Verificación Pre-lanzamiento

Antes de acceder al sitio, verifica que tengas:

- [x] XAMPP instalado y funcionando
- [x] Composer instalado
- [x] Host virtual configurado (`luzelfaro.com`)
- [x] Archivo hosts del sistema actualizado
- [x] Archivo `.env` configurado
- [x] Base de datos creada con `scripts_tables.sql`
- [x] Datos de prueba insertados con `dataset.sql`
- [x] Dependencias instaladas con `composer install`

### 🌟 Iniciar el Sistema

1. **Iniciar servicios XAMPP**:
   - Abrir el Panel de Control de XAMPP
   - Iniciar **Apache** ✅
   - Iniciar **MySQL** ✅

2. **Acceder al sistema**:
   ```
   🌐 http://luzelfaro.com
   ```

### 👤 Usuarios de Prueba

Una vez que accedas al sistema, podrás usar estos usuarios de prueba:

| Rol | Usuario | Contraseña | Descripción |
|-----|---------|------------|-------------|
| **Administrador** | `admin@luzelfaro.com` | `admin123` | Acceso completo al sistema |
| **Agente** | `agente1@luzelfaro.com` | `agente123` | Gestión de tickets asignados |
| **Cliente** | `cliente1@luzelfaro.com` | `cliente123` | Creación y seguimiento de tickets |

> 🔐 **Seguridad**: Cambia estas contraseñas en producción

---

## 🐛 Resolución de Problemas

### Problemas Comunes

**❌ Error: "No se puede conectar a la base de datos"**
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en el archivo `.env`
- Asegúrate de que la base de datos exista

**❌ Error: "Cannot resolve luzelfaro.com"**
- Verifica el archivo hosts del sistema
- Confirma la configuración del virtual host en Apache
- Reinicia el sistema si es necesario

**❌ Error: "Composer command not found"**
- Instala Composer desde [getcomposer.org](https://getcomposer.org/)
- Reinicia la terminal después de la instalación

**❌ Las imágenes o CSS no cargan**
- Verifica que el DocumentRoot apunte a la carpeta `public`
- Confirma que uses exactamente `luzelfaro.com` como ServerName

---

## 📞 Soporte

Si encuentras problemas durante la instalación o uso del sistema:

1. **Revisa los logs de error**:
   - Apache: `P:\XAMPP\apache\logs\error.log`
   - PHP: Configurado en el sistema

2. **Verifica la configuración**:
   - Archivo `.env`
   - Virtual host de Apache
   - Archivo hosts del sistema

3. **Documentación adicional**:
   - Consulta los comentarios en el código
   - Revisa la estructura de la base de datos en `scripts_tables.sql`

---

## 🏆 Créditos

**Desarrollado por**: [Tu Nombre]  
**Versión**: 1.0.0  
**Licencia**: MIT  

---

*¡Gracias por usar el Sistema SARC! 🎉*
