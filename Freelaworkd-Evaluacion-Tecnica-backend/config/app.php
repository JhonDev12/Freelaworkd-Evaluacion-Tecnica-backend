<?php

use App\Providers\RepositoryServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Este valor define el nombre de la aplicación. Puede utilizarse en 
    | notificaciones, encabezados de vistas u otros contextos donde se 
    | requiera identificar la instancia actual del sistema.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | Determina el entorno actual de ejecución de la aplicación 
    | (local, staging, production, etc.). Se define en el archivo .env.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | Activa el modo debug, mostrando trazas de errores detalladas.
    | En producción debe estar deshabilitado por seguridad.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | URL base del proyecto. Es utilizada internamente por Artisan 
    | para la generación de rutas absolutas y notificaciones.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Define la zona horaria por defecto para el framework y PHP.
    | Se recomienda ajustarla al país de despliegue.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | Configura el idioma principal de la aplicación.
    | También permite definir un idioma de respaldo y uno para Faker.
    |
    */

    'locale' => env('APP_LOCALE', 'es'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'es_ES'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | Clave utilizada por el servicio de encriptación de Laravel.
    | Debe tener 32 caracteres aleatorios para garantizar seguridad.
    |
    */

    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Configuración del modo de mantenimiento.
    | Se puede manejar mediante “file” o “cache” driver.
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | Aquí se listan todos los providers cargados por el framework.
    | Incluye tanto los predeterminados de Laravel como los propios
    | del proyecto, como los providers de repositorios personalizados.
    |
    | RepositoryServiceProvider:
    | - Registra las interfaces y sus implementaciones concretas.
    | - Aplica el principio de inversión de dependencias (SOLID).
    | - Facilita el desacoplamiento entre servicios y persistencia.
    |
    */

  'providers' => [

    /*
    |--------------------------------------------------------------------------
    | Core Laravel Providers
    |--------------------------------------------------------------------------
    |
    | Providers esenciales del framework. No deben eliminarse.
    | Registran los servicios fundamentales: base de datos, archivos, colas, etc.
    |
    */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class, // 👈 Agrega este
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Filesystem\FilesystemServiceProvider::class,
    Illuminate\Foundation\Providers\FoundationServiceProvider::class,
    Illuminate\View\ViewServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    |
    | Providers específicos del proyecto Freelaworkd.
    |
    */
    App\Providers\AppServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Custom Providers
    |--------------------------------------------------------------------------
    |
    | Extensiones adicionales para el dominio del sistema (DDD, SOLID).
    |
    */
    App\Providers\RepositoryServiceProvider::class,
    Illuminate\Hashing\HashServiceProvider::class,
     Illuminate\Session\SessionServiceProvider::class,

],

];
