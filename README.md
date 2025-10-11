FREELAWORKD — EVALUACIÓN TÉCNICA (BACKEND LARAVEL)

Descripción
Proyecto backend desarrollado para la evaluación técnica Full Stack (Laravel/Vue.js) de Iyata. Implementa autenticación con Laravel Sanctum, CRUDs principales (usuarios, roles, habilidades, proyectos, propuestas) y prácticas de arquitectura limpias con estándares PSR-12 y principios SOLID, priorizando claridad, escalabilidad, seguridad y mantenibilidad.

CREDENCIALES DE ACCESO (ENTORNO LOCAL / DEMO)

Estas credenciales se crean mediante los seeders para facilitar la revisión.

Rol: Super Admin
Email: jhon@example.com

Contraseña: 12345678

IMPORTANTE: No usar estas credenciales en producción. Cámbialas y regenera la contraseña antes de desplegar.

REQUISITOS PREVIOS

PHP 8.x o superior

Composer

MySQL o MariaDB

Extensiones PHP: pdo, mbstring, tokenizer, ctype, json, openssl

Git

Servidor local (Apache/Nginx) o “php artisan serve”

Opcional: Docker y Docker Compose

CLONACIÓN DEL REPOSITORIO

git clone https://github.com/JhonDev12/Freelaworkd-Evaluacion-Tecnica-backend.git

cd Freelaworkd-Evaluacion-Tecnica-backend/Freelaworkd-Evaluacion-Tecnica-backend

Verifica que te encuentres en el directorio del backend antes de continuar.

INSTALACIÓN DE DEPENDENCIAS

composer install

CONFIGURACIÓN DEL ENTORNO

Copiar el archivo de entorno de ejemplo:
cp .env.example .env

Editar .env con tus valores locales mínimos:
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

Generar la clave de aplicación:
php artisan key:generate

(Recomendado para integración SPA con Sanctum)
En .env y config/cors.php habilitar supports_credentials=true.
Configurar SANCTUM_STATEFUL_DOMAINS con el host del frontend (por ejemplo: localhost:5173).

MIGRACIONES Y SEEDERS

Aplicar migraciones:
php artisan migrate

Sembrar datos base (roles, usuario Super Admin y datasets de prueba):
php artisan db:seed --class=Database\Seeders\DatabaseSeeder
(Si necesitas ejecutar por partes, primero: php artisan db:seed --class=Database\Seeders\RoleSeeder)

Al finalizar, podrás iniciar sesión con el Super Admin indicado arriba.

ESTRUCTURA DE BASE DE DATOS (RESUMEN)

roles → define roles del sistema
users → usuarios, pertenece a roles; relaciones con habilidades, proyectos, propuestas
habilidades → catálogo de habilidades
user_habilidad (pivot) → relación muchos a muchos entre usuarios y habilidades
proyectos → proyectos creados por usuarios
propuestas → postulaciones/ofertas a proyectos (usuario y proyecto asociados)
personal_access_tokens → tokens de Sanctum
password_reset_tokens, sessions → tablas auxiliares del framework

Relaciones Eloquent principales:

User belongsTo Role

User belongsToMany Habilidad (tabla pivote: user_habilidad)

User hasMany Proyecto

User hasMany Propuesta (clave foránea usuario_id)

Proyecto belongsTo User, Proyecto hasMany Propuesta

Propuesta belongsTo Proyecto, Propuesta belongsTo User (usuario_id)

Criterios de integridad:

Claves foráneas con onDelete('cascade') u onDelete('set null') según el caso

Normalización en 3FN para evitar duplicidades

API (PREFIJO /api) Y AUTENTICACIÓN

Autenticación (Laravel Sanctum). Para SPA con cookies, configurar correctamente CORS y SANCTUM_STATEFUL_DOMAINS. Alternativamente, pueden usarse tokens personales.

Autenticación (/api/auth)

POST /api/auth/registro → registro de usuario (público)

POST /api/auth/login → login y emisión de cookie/token (público)

GET /api/auth/user → usuario autenticado (auth:sanctum)

POST /api/auth/logout → logout y revocación (auth:sanctum)

Proyectos (/api/proyectos)

GET /api/proyectos → listar (auth)

POST /api/proyectos → crear (auth)

GET /api/proyectos/{id} → ver (auth)

PUT /api/proyectos/{id} → actualizar (auth)

DELETE /api/proyectos/{id} → eliminar (auth)
Notas: estado con dominio controlado: abierto, en progreso, finalizado.

Propuestas (/api/propuestas)

GET /api/propuestas → listar (auth)

POST /api/propuestas → crear (auth)

GET /api/propuestas/{id} → ver (auth)

PUT /api/propuestas/{id} → actualizar (auth)

DELETE /api/propuestas/{id} → eliminar (auth)
Notas: pertenece a usuario (usuario_id) y proyecto (proyecto_id); incluye descripcion, presupuesto, tiempo_estimado.

Usuarios (/api/usuarios)

GET /api/usuarios → listar (auth)

POST /api/usuarios → crear (solo admin)

GET /api/usuarios/{id} → ver (auth)

PUT /api/usuarios/{id} → actualizar (auth)

DELETE /api/usuarios/{id} → eliminar (auth)

PATCH /api/usuarios/{id}/rol → asignar rol (requiere permisos elevados)

PATCH /api/usuarios/{id}/habilidades → gestionar habilidades del usuario (auth)
Notas: validar permisos con Gates/Policies o middleware (por ejemplo, role:super_admin).

Roles (/api/roles)

GET /api/roles → listar (auth)

POST /api/roles → crear (solo super admin)

GET /api/roles/{id} → ver (auth)

PUT /api/roles/{id} → actualizar (solo super admin)

DELETE /api/roles/{id} → eliminar (solo super admin)

Habilidades (/api/habilidades)

GET /api/habilidades → listar (auth)

POST /api/habilidades → crear (auth)

GET /api/habilidades/{id} → ver (auth)

PUT /api/habilidades/{id} → actualizar (auth)

DELETE /api/habilidades/{id} → eliminar (auth)
Notas: asignación a usuarios mediante la tabla pivote user_habilidad.

Fallback global

Respuesta 404 JSON: { "mensaje": "Ruta no encontrada." }

HERRAMIENTAS DE CALIDAD Y ANÁLISIS ESTÁTICO

Laravel Pint (formato PSR-12)

Instalación: composer require laravel/pint --dev

Uso: vendor/bin/pint

Larastan (PHPStan para Laravel)

Instalación: composer require --dev nunomaduro/larastan

Publicar config: php artisan vendor:publish --provider="NunoMaduro\Larastan\LarastanServiceProvider"

Configuración sugerida (phpstan.neon.dist):
parameters:
level: 6
paths:
- app
excludePaths:
- vendor

Análisis: vendor/bin/phpstan analyse

Xdebug (cobertura con PHPUnit)

Verificar instalación: php -v

Cobertura: php artisan test --coverage

Objetivo sugerido de cobertura mínima: ≥ 60%

EJECUCIÓN LOCAL RÁPIDA

composer install

cp .env.example .env

php artisan key:generate

Configurar DB en .env y crear la base “freelaworkd”

php artisan migrate

php artisan db:seed --class=Database\Seeders\DatabaseSeeder

php artisan serve

Iniciar sesión con:
Email: jhon@example.com

Contraseña: 12345678

CONSIDERACIONES DE SEGURIDAD

No exponer credenciales reales en repositorios públicos.

Rotar y encriptar secretos al desplegar.

Habilitar HTTPS y CORS con supports_credentials=true cuando se use sesión/cookies en SPA.

Proteger mutaciones sensibles con Policies o middleware específicos.

Responder únicamente JSON para minimizar superficies de ataque.

PRUEBAS

Ejecutar suite: php artisan test

Cobertura (opcional): php artisan test --coverage

Pruebas incluidas: autenticación, creación/edición/eliminación de roles, validaciones y endpoints principales.

DECISIONES TÉCNICAS (RESUMEN)

Arquitectura REST con controladores finos y capa de servicios/repositorios donde aplica.

Integridad referencial consistente y relaciones Eloquent expresivas.

Normalización 3FN y dominios controlados en campos clave.

Laravel Sanctum para autenticación SPA y/o tokens personales.

Código conforme a PSR-12, análisis estático y cobertura de pruebas.

AUTOR

Desarrollador: Jhon Smith Meneses
Rol: Full Stack Developer
Stack: Laravel, Sanctum, MySQL, REST API
Repositorio: Freelaworkd — Evaluación Técnica Backend

NOTAS FINALES

Este backend cumple los criterios de la evaluación técnica de Iyata, con foco en buenas prácticas, documentación clara y una base sólida para integrar el frontend en Vue 3. Para producción, reemplazar credenciales seed, ajustar CORS/HTTPS y revisar Policies de acceso según los perfiles reales.