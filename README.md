# SINET - Sistema de Autogestión ComfaChoco

Sistema integral de gestión de vacaciones, permisos y ausentismos para empleados de ComfaChoco, desarrollado con Laravel 12 y Jetstream.

## 📋 Descripción

SINET (anteriormente Auto Gestión ComfaChoco) es una aplicación web que permite a los empleados solicitar y gestionar sus vacaciones, permisos y licencias de manera digital. El sistema cuenta con diferentes roles (Admin, RRHH, Supervisor, Empleado) con permisos específicos para cada uno.

## 🎯 Características Principales

- **Gestión de Vacaciones**: Solicitud y aprobación de vacaciones con cálculo automático de días acumulados
- **Gestión de Permisos**: Tramitación de permisos y licencias (citas médicas, permisos personales, maternidad, paternidad, luto)
- **Sistema de Roles y Permisos**: Control granular de acceso usando Spatie Laravel-Permission
- **Dashboard Personalizado**: Vista diferenciada según rol del usuario
- **Notificaciones**: Alertas para solicitudes pendientes y cambios de estado
- **Validaciones Inteligentes**: Control de fechas, días acumulados y días hábiles
- **Interfaz Moderna**: Diseño intuitivo con Tailwind CSS y componentes personalizados

## 🚀 Requisitos del Sistema

- **PHP** >= 8.2
- **Composer** >= 2.x
- **MySQL** >= 8.0 o MariaDB >= 10.6
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **Git**

### Extensiones PHP Requeridas

- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD

## 📦 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/Camilotabares/AutogestionComfaChoco.git
cd AutogestionComfaChoco
```

### 2. Instalar Dependencias PHP

```bash
composer install
```

### 3. Instalar Dependencias JavaScript

```bash
npm install
```

### 4. Configurar Variables de Entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` con las configuraciones de tu entorno:

```env
APP_NAME="SINET"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comfachoco
DB_USERNAME=root
DB_PASSWORD=

# Configuración de correo (opcional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 6. Crear la Base de Datos

Crear manualmente la base de datos en MySQL:

```sql
CREATE DATABASE comfachoco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar Migraciones y Seeders

```bash
php artisan migrate --seed
```

Esto creará todas las tablas y datos iniciales:
- Roles: Admin, RRHH, Supervisor, Empleado
- Permisos: Sistema completo de permisos para cada módulo
- Usuario administrador por defecto

### 8. Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

### 9. Configurar Permisos de Carpetas

```bash
chmod -R 775 storage bootstrap/cache
```

Si usas Linux/Mac y tienes problemas de permisos:

```bash
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 10. Compilar Assets

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

## 🏃 Ejecutar el Proyecto

### Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

**Nota:** Si el puerto 8000 está ocupado, puedes especificar otro:
```bash
php artisan serve --port=8001
```

Recuerda actualizar `APP_URL` en `.env` si cambias el puerto.

### Compilar Assets en Modo Watch (Desarrollo)

En otra terminal, ejecuta:
```bash
npm run dev
```

Esto mantendrá los assets compilándose automáticamente cuando hagas cambios.

## 👤 Credenciales por Defecto

Después de ejecutar los seeders, se crearán usuarios de prueba:

**Administrador:**
- Email: `admin@comfachoco.com`
- Password: `password`

**RRHH:**
- Email: `rrhh@comfachoco.com`
- Password: `password`

**Supervisor:**
- Email: `supervisor@comfachoco.com`
- Password: `password`

**Empleado:**
- Email: `empleado@comfachoco.com`
- Password: `password`

> ⚠️ **Importante:** Cambiar estas credenciales en producción.

## 📁 Estructura del Proyecto

```
AutogestionComfaChoco/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/          # Controladores del panel admin
│   │   └── Middleware/
│   ├── Models/                 # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Empleado.php
│   │   ├── Vacacion.php
│   │   ├── Permisos.php
│   │   └── SolicitudVacacion.php
│   └── Livewire/               # Componentes Livewire
├── database/
│   ├── migrations/             # Migraciones de base de datos
│   └── seeders/                # Seeders (datos iniciales)
├── resources/
│   ├── views/                  # Vistas Blade
│   │   ├── admin/              # Vistas del panel admin
│   │   ├── layouts/
│   │   └── components/
│   ├── css/                    # Estilos CSS
│   └── js/                     # JavaScript
├── routes/
│   ├── web.php                 # Rutas públicas
│   ├── admin.php               # Rutas del panel admin
│   └── api.php                 # Rutas API
└── public/                     # Archivos públicos
```

## 🔐 Sistema de Roles y Permisos

El sistema utiliza **Spatie Laravel-Permission** para gestionar roles y permisos:

### Roles Disponibles

1. **Admin**: Acceso completo a todas las funcionalidades
2. **RRHH**: Gestión de empleados, aprobación de solicitudes >2 días
3. **Supervisor**: Aprobación de solicitudes ≤2 días
4. **Empleado**: Solicitud de vacaciones y permisos propios

### Módulos de Permisos

- **empleados**: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
- **vacaciones**: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
- **permisos**: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
- **roles**: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
- **solicitudes**: `index`, `show`, `aprobar`, `rechazar`

## ⚙️ Funcionalidades Clave

### Gestión de Vacaciones

- Cálculo automático de días acumulados (máximo 30 días)
- Validación de fechas (no permitir fechas pasadas)
- Aprobación según jerarquía (Supervisor ≤2 días, RRHH >2 días)
- Historial completo de solicitudes

### Gestión de Permisos

- Tipos de permisos:
  - **Ausentismo**: Citas médicas, permisos personales
  - **Licencias**: Luto, maternidad, paternidad
- Validación de días hábiles vs. duración total
- Carga de documentos de soporte
- Estados: Pendiente, Aprobado, Rechazado

### Validaciones Implementadas

1. **Fechas**: No se permiten fechas pasadas
2. **Días hábiles**: Deben ser ≤ duración total del permiso
3. **Días acumulados**: Control de hasta 30 días para vacaciones
4. **Permisos por acción**: Middleware granular por cada acción CRUD

## 🛠️ Comandos Útiles de Artisan

```bash
# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Refrescar base de datos (borra todo)
php artisan migrate:fresh --seed

# Ver rutas disponibles
php artisan route:list

# Ver permisos y roles
php artisan permission:show

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🐛 Solución de Problemas Comunes

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied"
Verifica las credenciales de base de datos en `.env`

### Error: "The stream or file could not be opened"
```bash
chmod -R 775 storage bootstrap/cache
```

### Las fotos de perfil no cargan
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

Verifica que `APP_URL` en `.env` tenga el puerto correcto.

### Los cambios CSS no se reflejan
```bash
npm run build
php artisan view:clear
```

## 🌐 Despliegue en Producción

1. **Configurar `.env` para producción:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
```

2. **Optimizar aplicación:**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. **Configurar permisos:**
```bash
chmod -R 755 storage bootstrap/cache
```

4. **Configurar servidor web** (Nginx/Apache) para apuntar a `/public`

## 📝 Notas de Desarrollo

### Ramas

- `main`: Rama principal (producción)
- `funcionalidades`: Desarrollo de nuevas funcionalidades

### Workflow de Git

```bash
# Crear nueva rama
git checkout -b nombre-feature

# Hacer cambios
git add .
git commit -m "Descripción del cambio"

# Subir cambios
git push -u origin nombre-feature

# Merge a funcionalidades
git checkout funcionalidades
git merge nombre-feature
git push
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es privado y propiedad de ComfaChoco.

## 👨‍💻 Desarrolladores

- **Equipo de Desarrollo ComfaChoco**

## 📧 Soporte

Para soporte técnico, contactar a: soporte@comfachoco.com

---

**Última actualización:** Octubre 2025  
**Versión:** 1.0.0

7. **Ejecutar las migraciones y seeders**
```bash
php artisan migrate --seed
```

8. **Crear enlace simbólico para el storage**
```bash
php artisan storage:link
```

9. **Compilar assets**
```bash
npm run build
```

10. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

## 🔐 Credenciales de Acceso

Después de ejecutar los seeders (`php artisan migrate --seed`), puedes ingresar con las siguientes credenciales:

### Administrador
- **Email:** admin@comfachoco.com
- **Contraseña:** password
- **Rol:** Administrador del sistema con acceso completo

### Recursos Humanos (RRHH)
- **Email:** rrhh@comfachoco.com
- **Contraseña:** password
- **Rol:** Jefe de RRHH - Aprueba solicitudes mayores a 2 días

### Supervisor
- **Email:** supervisor@comfachoco.com
- **Contraseña:** password
- **Rol:** Supervisor de área - Aprueba solicitudes de 1-2 días

### Empleado
- **Email:** empleado@comfachoco.com
- **Contraseña:** password
- **Rol:** Empleado regular - Puede solicitar vacaciones y permisos

## 📋 Funcionalidades

- ✅ Gestión de solicitudes de vacaciones
- ✅ Gestión de permisos y ausentismos
- ✅ Sistema de aprobaciones por roles (Supervisor/RRHH)
- ✅ Consulta de solicitudes pendientes, aprobadas y rechazadas
- ✅ Cálculo manual de días hábiles
- ✅ Notificaciones con SweetAlert
- ✅ Sistema de permisos con Spatie Laravel-Permission

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel 11
- **Frontend:** Blade Templates, Livewire, TailwindCSS
- **Base de datos:** MySQL
- **Autenticación:** Laravel Fortify + Jetstream
- **Permisos:** Spatie Laravel-Permission
- **Iconos:** Font Awesome

## 📝 Notas Importantes

- Las solicitudes de **1-2 días hábiles** son aprobadas por el **Supervisor**
- Las solicitudes de **más de 2 días hábiles** son aprobadas por **RRHH**
- Los días hábiles se ingresan manualmente al crear la solicitud
- Todos los usuarios deben tener un registro de empleado asociado

## 🤝 Contribuir

Si deseas contribuir al proyecto, por favor:
1. Haz un fork del repositorio
2. Crea una rama con tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es propiedad de ComfaChoco.
