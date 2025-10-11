# FREELAWORKD — EVALUACIÓN TÉCNICA (BACKEND LARAVEL)

## Descripción
Proyecto backend desarrollado para la evaluación técnica **Full Stack (Laravel/Vue.js)** de **Iyata**.  
Implementa autenticación con **Laravel Sanctum**, CRUDs principales (usuarios, roles, habilidades, proyectos, propuestas) y prácticas de arquitectura limpias con estándares **PSR-12** y principios **SOLID**, priorizando claridad, escalabilidad, seguridad y mantenibilidad.

---

## CREDENCIALES DE ACCESO (ENTORNO LOCAL / DEMO)

Estas credenciales se crean mediante los seeders para facilitar la revisión.

**Rol:** Super Admin  
**Email:** `jhon@example.com`  
**Contraseña:** `12345678`

IMPORTANTE: No usar estas credenciales en producción. Cámbialas y regenera la contraseña antes de desplegar.

---

## REQUISITOS PREVIOS

- PHP 8.x o superior  
- Composer  
- MySQL o MariaDB  
- Extensiones PHP: pdo, mbstring, tokenizer, ctype, json, openssl  
- Git  
- Servidor local (Apache/Nginx) o comando `php artisan serve`  
- Opcional: Docker y Docker Compose

---

## CLONACIÓN DEL REPOSITORIO

```bash
git clone https://github.com/JhonDev12/Freelaworkd-Evaluacion-Tecnica-backend.git
cd Freelaworkd-Evaluacion-Tecnica-backend/Freelaworkd-Evaluacion-Tecnica-backend
```

Verifica que te encuentres en el directorio correcto del backend antes de continuar.

---

## INSTALACIÓN DE DEPENDENCIAS

```bash
composer install
```

---

## CONFIGURACIÓN DEL ENTORNO

Copiar el archivo de entorno de ejemplo:  
```bash
cp .env.example .env
```

Editar `.env` con tus valores locales mínimos:

```
APP_NAME=Freelaworkd
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freelaworkd
DB_USERNAME=root
DB_PASSWORD=
```

Generar la clave de aplicación:  
```bash
php artisan key:generate
```

(Recomendado para integración SPA con Sanctum)  
En `.env` y `config/cors.php` habilitar `supports_credentials=true`.  
Configurar `SANCTUM_STATEFUL_DOMAINS` con el host del frontend (por ejemplo: `localhost:5173`).

---

## MIGRACIONES Y SEEDERS

Aplicar migraciones:  
```bash
php artisan migrate:fresh --seed
```

Sembrar datos base (roles, usuario Super Admin y datasets de prueba):  
```bash
php artisan db:seed --class=Database\Seeders\DatabaseSeeder
```

(Si necesitas ejecutar por partes:  
```bash
php artisan db:seed --class=Database\Seeders\RoleSeeder
```)

Al finalizar, podrás iniciar sesión con el Super Admin indicado arriba.

---

## ESTRUCTURA DE BASE DE DATOS (RESUMEN)

- **roles** → define roles del sistema  
- **users** → usuarios, pertenece a roles; relaciones con habilidades, proyectos, propuestas  
- **habilidades** → catálogo de habilidades  
- **user_habilidad (pivot)** → relación muchos a muchos entre usuarios y habilidades  
- **proyectos** → proyectos creados por usuarios  
- **propuestas** → postulaciones/ofertas a proyectos (usuario y proyecto asociados)  
- **personal_access_tokens** → tokens de Sanctum  
- **password_reset_tokens, sessions** → tablas auxiliares del framework

Relaciones Eloquent principales:

- User belongsTo Role  
- User belongsToMany Habilidad (tabla pivote: user_habilidad)  
- User hasMany Proyecto  
- User hasMany Propuesta (clave foránea usuario_id)  
- Proyecto belongsTo User, Proyecto hasMany Propuesta  
- Propuesta belongsTo Proyecto, Propuesta belongsTo User (usuario_id)

Criterios de integridad:

- Claves foráneas con `onDelete('cascade')` u `onDelete('set null')` según el caso.  
- Normalización en 3FN para evitar duplicidades.

---

## API (PREFIJO /api) Y AUTENTICACIÓN

Autenticación (Laravel Sanctum). Para SPA con cookies, configurar correctamente CORS y `SANCTUM_STATEFUL_DOMAINS`.  
Alternativamente, pueden usarse tokens personales.

### Autenticación (/api/auth)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| POST | /api/auth/registro | Registro de usuario | Público |
| POST | /api/auth/login | Login y emisión de token/cookie | Público |
| GET  | /api/auth/user | Usuario autenticado | auth:sanctum |
| POST | /api/auth/logout | Logout y revocación | auth:sanctum |

### Proyectos (/api/proyectos)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| GET | /api/proyectos | Listar proyectos | Auth |
| POST | /api/proyectos | Crear proyecto | Auth |
| GET | /api/proyectos/{id} | Ver proyecto | Auth |
| PUT | /api/proyectos/{id} | Actualizar proyecto | Auth |
| DELETE | /api/proyectos/{id} | Eliminar proyecto | Auth |

Notas: El campo `estado` usa dominio controlado: `abierto`, `en progreso`, `finalizado`.

### Propuestas (/api/propuestas)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| GET | /api/propuestas | Listar propuestas | Auth |
| POST | /api/propuestas | Crear propuesta | Auth |
| GET | /api/propuestas/{id} | Ver propuesta | Auth |
| PUT | /api/propuestas/{id} | Actualizar propuesta | Auth |
| DELETE | /api/propuestas/{id} | Eliminar propuesta | Auth |

### Usuarios (/api/usuarios)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| GET | /api/usuarios | Listar usuarios | Auth |
| POST | /api/usuarios | Crear usuario | Admin |
| GET | /api/usuarios/{id} | Ver usuario | Auth |
| PUT | /api/usuarios/{id} | Actualizar usuario | Auth |
| DELETE | /api/usuarios/{id} | Eliminar usuario | Auth |
| PATCH | /api/usuarios/{id}/rol | Asignar rol | Super Admin |
| PATCH | /api/usuarios/{id}/habilidades | Gestionar habilidades | Auth |

### Roles (/api/roles)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| GET | /api/roles | Listar roles | Auth |
| POST | /api/roles | Crear rol | Super Admin |
| GET | /api/roles/{id} | Ver rol | Auth |
| PUT | /api/roles/{id} | Actualizar rol | Super Admin |
| DELETE | /api/roles/{id} | Eliminar rol | Super Admin |

### Habilidades (/api/habilidades)
| Método | Ruta | Descripción | Acceso |
|--------|-------|-------------|---------|
| GET | /api/habilidades | Listar habilidades | Auth |
| POST | /api/habilidades | Crear habilidad | Auth |
| GET | /api/habilidades/{id} | Ver habilidad | Auth |
| PUT | /api/habilidades/{id} | Actualizar habilidad | Auth |
| DELETE | /api/habilidades/{id} | Eliminar habilidad | Auth |

---

## HERRAMIENTAS DE CALIDAD Y ANÁLISIS ESTÁTICO

### Laravel Pint (formato PSR-12)
```bash
composer require laravel/pint --dev
vendor/bin/pint
```

### Larastan (PHPStan para Laravel)
```bash
composer require --dev nunomaduro/larastan
php artisan vendor:publish --provider="NunoMaduro\Larastan\LarastanServiceProvider"
vendor/bin/phpstan analyse
```

Configuración sugerida (`phpstan.neon.dist`):
```yaml
parameters:
  level: 6
  paths:
    - app
  excludePaths:
    - vendor
```

### Xdebug (cobertura con PHPUnit)
```bash
php artisan test --coverage
```

Objetivo sugerido de cobertura mínima: **≥ 60%**.

---

## EJECUCIÓN LOCAL RÁPIDA

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=Database\Seeders\DatabaseSeeder
php artisan serve
```

Iniciar sesión con:  
Email: `jhon@example.com`  
Contraseña: `12345678`

---

## CONSIDERACIONES DE SEGURIDAD

- No exponer credenciales reales en repositorios públicos.  
- Rotar y encriptar secretos antes del despliegue.  
- Habilitar HTTPS y CORS con `supports_credentials=true` cuando se use sesión SPA.  
- Proteger mutaciones sensibles con Policies o middleware específicos.  
- Responder únicamente en JSON para reducir superficie de ataque.

---

## PRUEBAS

```bash
php artisan test
php artisan test --coverage
```

Pruebas incluidas:
- Autenticación (login y registro).  
- Creación, edición y eliminación de roles.  
- Validaciones de campos obligatorios.  
- Cobertura de endpoints principales.

---

## DECISIONES TÉCNICAS (RESUMEN)

- Arquitectura REST con controladores finos y capa de servicios/repositorios.  
- Integridad referencial y relaciones Eloquent expresivas.  
- Normalización 3FN y dominios controlados.  
- Laravel Sanctum para autenticación SPA o tokens personales.  
- Código conforme a PSR-12, análisis estático y cobertura de pruebas.

---

## AUTOR

**Jhon Smith Meneses**  
Rol: **Full Stack Developer**  
Stack: **Laravel, Sanctum, MySQL, REST API**  
Repositorio: **Freelaworkd — Evaluación Técnica Backend**

---

## NOTAS FINALES

Este backend cumple con los criterios de la evaluación técnica de **Iyata**, con foco en buenas prácticas, documentación clara y base sólida para integrar el frontend en Vue 3.  
Para producción, reemplazar credenciales seed, ajustar CORS/HTTPS y revisar Policies de acceso según los perfiles reales.
