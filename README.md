# Sistema de Autogestión - ComfaChoco

Sistema de gestión de vacaciones y permisos para ComfaChoco, desarrollado con Laravel 11.

## 🚀 Requisitos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18.x
- NPM

## 📦 Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Camilotabares/AutogestionComfaChoco.git
cd AutogestionComfaChoco
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el archivo de entorno**
```bash
cp .env.example .env
```

5. **Editar el archivo `.env` con las credenciales de tu base de datos**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

6. **Generar la clave de aplicación**
```bash
php artisan key:generate
```

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
