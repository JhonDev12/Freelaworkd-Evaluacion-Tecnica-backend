Freelaworkd — Evaluación Técnica Backend

Evaluación técnica desarrollada como parte del proceso Full Stack Junior (Laravel/Vue.js) para Iyata.
Este backend fue diseñado siguiendo buenas prácticas de arquitectura, estándares PSR-12 y principios SOLID, priorizando claridad, escalabilidad, seguridad y mantenibilidad.

Requisitos Previos

Antes de instalar el proyecto, asegúrate de contar con el siguiente entorno configurado:

PHP 8.x o superior

Composer

MySQL o MariaDB

Extensiones PHP requeridas: pdo, mbstring, tokenizer, ctype, json, openssl

Git

Servidor local Apache o Nginx (o usar php artisan serve)

Opcional: Docker y Docker Compose

Clonación del Repositorio
git clone https://github.com/JhonDev12/Freelaworkd-Evaluacion-Tecnica-backend.git
cd Freelaworkd-Evaluacion-Tecnica-backend/Freelaworkd-Evaluacion-Tecnica-backend


Verifica que te encuentres en el directorio correcto del backend antes de continuar.

Instalación de Dependencias

Ejecuta el siguiente comando para instalar todas las dependencias definidas en el archivo composer.json:

composer install

Configuración del Entorno

Copia el archivo de entorno de ejemplo y renómbralo a .env:

cp .env.example .env


Edita el archivo .env con tus valores locales:

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


Genera la clave de aplicación:

php artisan key:generate

Migraciones y Seeders

El proyecto utiliza migraciones para definir y versionar la base de datos.
Esto permite replicar fácilmente la estructura en cualquier entorno con un solo comando.

Ejecutar las migraciones:

php artisan migrate


Si deseas poblar datos iniciales (roles, usuarios de prueba, etc.):

php artisan db:seed

Estructura de Base de Datos

La siguiente tabla describe las entidades principales del sistema, su propósito y relaciones dentro del modelo Eloquent.

Tabla	Propósito	Relaciones Principales
roles	Define los roles del sistema (administrador, cliente, freelancer, etc.).	hasMany(User)

users	Registra los usuarios del sistema.	belongsTo(Role), belongsToMany(Habilidad), hasMany(Proyecto), hasMany(Propuesta)

habilidades	Contiene las habilidades disponibles.	belongsToMany(User)
user_habilidad	Tabla pivote usuario ↔ habilidad.	user_id → users.id, habilidad_id → habilidades.id

proyectos	Proyectos creados por los usuarios.	belongsTo(User), hasMany(Propuesta)

propuestas	Ofertas o postulaciones a proyectos.	belongsTo(User, 'usuario_id'), belongsTo(Proyecto)

personal_access_tokens	Tokens personales generados por Laravel Sanctum.	morphs('tokenable')

password_reset_tokens, sessions	Tablas auxiliares internas del framework.	—

Relaciones Eloquent

Modelo User.php

public function role()
{
    return $this->belongsTo(Role::class);
}

public function habilidades()
{
    return $this->belongsToMany(Habilidad::class, 'user_habilidad');
}

public function proyectos()
{
    return $this->hasMany(Proyecto::class);
}

public function propuestas()
{
    return $this->hasMany(Propuesta::class, 'usuario_id');
}

Modelo Proyecto.php
public function usuario()
{
    return $this->belongsTo(User::class);
}

public function propuestas()
{
    return $this->hasMany(Propuesta::class);
}

Modelo Propuesta.php
public function proyecto()
{
    return $this->belongsTo(Proyecto::class);
}

public function usuario()
{
    return $this->belongsTo(User::class, 'usuario_id');
}

Decisiones Técnicas Justificadas

Integridad referencial completa: todas las claves foráneas incluyen onDelete('cascade') o onDelete('set null') según el caso.

Relación muchos a muchos entre usuarios y habilidades implementada mediante la tabla pivote user_habilidad.

Campos ENUM para definir dominios controlados (como estado en proyectos y nivel en habilidades).

Autenticación implementada con Laravel Sanctum, permitiendo sesiones seguras y tokens personales.

Estructura modular y extensible que permite agregar fácilmente nuevas entidades sin modificar las existentes.

Normalización de la base de datos en tercera forma normal (3NF) para evitar duplicidad de datos.

Diseño preparado para soportar pruebas unitarias y de integración.

Herramientas de Calidad y Análisis Estático
Laravel Pint (Formateo de Código)

Laravel Pint asegura que todo el código cumpla con el estándar PSR-12 y mantiene un estilo uniforme.

Instalación:

composer require laravel/pint --dev


Ejecución:

vendor/bin/pint


Se recomienda ejecutarlo antes de cada commit o integrarlo al pipeline de CI/CD.

Larastan (PHPStan para Laravel)

Herramienta de análisis estático que detecta errores, tipos incorrectos y malas prácticas antes de la ejecución.

Instalación:

composer require --dev nunomaduro/larastan


Configuración inicial:

php artisan vendor:publish --provider="NunoMaduro\Larastan\LarastanServiceProvider"


Esto crea el archivo phpstan.neon.dist, donde se puede ajustar el nivel de análisis:

parameters:
  level: 6
  paths:
    - app
  excludePaths:
    - vendor


Ejecución:

vendor/bin/phpstan analyse


Un nivel de 5 a 7 se considera adecuado para entornos productivos.

Xdebug (Cobertura de Código en Pruebas Unitarias)

Xdebug permite medir la cobertura del código en las pruebas ejecutadas con PHPUnit.

Pasos de instalación en Windows (XAMPP)

Visitar https://xdebug.org/download

En “Binarios de Windows”, seleccionar la versión correspondiente a tu PHP (por ejemplo, PHP 8.2 TS VS16 64 bits)

Descargar el archivo .dll (por ejemplo: php_xdebug-3.3.1-8.2-ts-vs16-x86_64.dll)

Mover el archivo a: C:\xampp\php\ext

Editar C:\xampp\php\php.ini y agregar al final:

zend_extension="C:\xampp\php\ext\php_xdebug-3.3.1-8.2-ts-vs16-x86_64.dll"
xdebug.mode=coverage


Guardar los cambios y reiniciar Apache desde el panel de XAMPP.

Verificar la instalación
php -v


Debe aparecer una línea similar a:

with Xdebug v3.3.1, Copyright (c) 2002-2024, by Derick Rethans


Si aparece, Xdebug está instalado correctamente.

Medir cobertura
php artisan test --coverage


Ejemplo de salida:

Coverage: 67.3%


Esto indica el porcentaje de líneas de código cubiertas por pruebas, cumpliendo con el estándar mínimo del 60% exigido en la evaluación de Iyata.

API Endpoints

La API sigue una arquitectura RESTful, con respuestas JSON estandarizadas y autenticación gestionada mediante Laravel Sanctum.
Todas las rutas protegidas requieren un token válido o una sesión activa generada por Sanctum.
El prefijo base de la API es /api.

1. Autenticación (/api/auth)
Método	Ruta	Descripción	Acceso
POST	/api/auth/registro	Registra un nuevo usuario.	Público
POST	/api/auth/login	Inicia sesión y genera token o cookie de sesión.	Público
GET	/api/auth/user	Retorna los datos del usuario autenticado.	Requiere auth:sanctum
POST	/api/auth/logout	Cierra sesión y revoca el token.	Requiere auth:sanctum

Notas técnicas:

La autenticación se basa en Laravel Sanctum (modo SPA y tokens personales).

Configurar correctamente SANCTUM_STATEFUL_DOMAINS y supports_credentials=true en config/cors.php para permitir flujo con cookies desde el frontend.

2. Proyectos (/api/proyectos)
Método	Ruta	Descripción	Acceso
GET	/api/proyectos	Lista todos los proyectos.	Autenticado
POST	/api/proyectos	Crea un nuevo proyecto.	Autenticado
GET	/api/proyectos/{id}	Muestra un proyecto específico.	Autenticado
PUT/PATCH	/api/proyectos/{id}	Actualiza un proyecto existente.	Autenticado
DELETE	/api/proyectos/{id}	Elimina un proyecto.	Autenticado

Detalles:
Los proyectos están asociados a un usuario (propietario).
El campo estado usa un dominio controlado: ['abierto', 'en progreso', 'finalizado'].

3. Propuestas (/api/propuestas)
Método	Ruta	Descripción	Acceso
GET	/api/propuestas	Lista todas las propuestas.	Autenticado
POST	/api/propuestas	Crea una propuesta vinculada a un proyecto.	Autenticado
GET	/api/propuestas/{id}	Muestra una propuesta específica.	Autenticado
PUT/PATCH	/api/propuestas/{id}	Actualiza una propuesta existente.	Autenticado
DELETE	/api/propuestas/{id}	Elimina una propuesta.	Autenticado

Detalles:
Cada propuesta pertenece a un usuario (usuario_id) y a un proyecto (proyecto_id), con atributos como descripcion, presupuesto y tiempo_estimado.

4. Usuarios (/api/usuarios)
Método	Ruta	Descripción	Acceso
GET	/api/usuarios	Lista todos los usuarios.	Autenticado
POST	/api/usuarios	Crea un nuevo usuario (solo admin).	Autenticado
GET	/api/usuarios/{id}	Muestra un usuario específico.	Autenticado
PUT/PATCH	/api/usuarios/{id}	Actualiza un usuario.	Autenticado
DELETE	/api/usuarios/{id}	Elimina un usuario.	Autenticado
PATCH	/api/usuarios/{id}/rol	Asigna un rol a un usuario.	Requiere permisos de administrador
PATCH	/api/usuarios/{id}/habilidades	Asigna o actualiza las habilidades de un usuario.	Autenticado

Notas:

Las mutaciones sobre roles o asignación de permisos deben validarse con Gates/Policies o un middleware role:super_admin.

Devuelve el usuario actualizado con sus relaciones (role, habilidades).

5. Roles (/api/roles)
Método	Ruta	Descripción	Acceso
GET	/api/roles	Lista los roles disponibles.	Autenticado
POST	/api/roles	Crea un nuevo rol.	Solo super admin
GET	/api/roles/{id}	Muestra un rol específico.	Autenticado
PUT/PATCH	/api/roles/{id}	Actualiza un rol.	Solo super admin
DELETE	/api/roles/{id}	Elimina un rol.	Solo super admin

Notas:
Se recomienda aplicar validaciones de permisos mediante Policies o middleware específicos.

6. Habilidades (/api/habilidades)
Método	Ruta	Descripción	Acceso
GET	/api/habilidades	Lista todas las habilidades.	Autenticado
POST	/api/habilidades	Crea una nueva habilidad.	Autenticado
GET	/api/habilidades/{id}	Muestra una habilidad específica.	Autenticado
PUT/PATCH	/api/habilidades/{id}	Actualiza una habilidad.	Autenticado
DELETE	/api/habilidades/{id}	Elimina una habilidad.	Autenticado

Notas:
Las habilidades pueden asignarse a múltiples usuarios mediante la tabla pivote user_habilidad.

7. Fallback Global

Si se accede a una ruta no definida, la API responde con:

{
  "mensaje": "Ruta no encontrada."
}


Código de estado HTTP: 404 Not Found.

Consideraciones de Seguridad

Todas las mutaciones sensibles (roles, asignaciones, eliminación) deben estar protegidas con middleware de autorización o Policies.

Se debe mantener CORS configurado con supports_credentials=true.

En el frontend (Vue), se recomienda usar withCredentials: true en Axios para mantener la sesión.

Toda la API responde únicamente en JSON, evitando respuestas HTML.

Testing

El proyecto está preparado para pruebas automáticas utilizando PHPUnit y el comando nativo de Laravel:

php artisan test


Pruebas implementadas:

Autenticación de usuarios (login y registro).

Creación, actualización y eliminación de roles.

Validaciones de campos obligatorios.

Cobertura general de endpoints principales.

Principios de Arquitectura Aplicados

SOLID: código modular, extensible y de fácil mantenimiento.

DRY (Don’t Repeat Yourself): se evita duplicar lógica mediante helpers, traits y servicios.

KISS (Keep It Simple): diseño claro y sin complejidades innecesarias.

Repository Pattern: separación de la capa de acceso a datos.

Service Layer: centralización de reglas de negocio fuera de los controladores.

Dependency Injection: mejora la testabilidad y reduce el acoplamiento.

Documentación Obligatoria (Requerida por Iyata)

README con instrucciones completas de instalación (backend y frontend).

Documentación de endpoints de la API.

Migraciones de Laravel definidas para toda la estructura de la base de datos.

Archivo .env.example con variables de entorno necesarias.

Decisiones técnicas justificadas.

Resumen de Herramientas de Calidad
Herramienta	Propósito	Comando Principal
Laravel Pint	Formateo de código PSR-12	vendor/bin/pint
Larastan	Análisis estático del código	vendor/bin/phpstan analyse
Xdebug	Medición de cobertura de pruebas	php artisan test --coverage
Autor

Desarrollador: Jhon Smith Meneses
Rol: Full Stack Developer
Stack: Laravel, Sanctum, MySQL, REST API
Repositorio: Freelaworkd Evaluación Técnica Backend

Conclusión

Este proyecto cumple con los criterios técnicos exigidos en la Evaluación Técnica de Iyata 2024.
La implementación se realizó siguiendo un enfoque profesional, priorizando calidad, documentación y consistencia técnica.
El resultado es un backend funcional, escalable y alineado con buenas prácticas de desarrollo moderno en Laravel.
