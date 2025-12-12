# 🚀 Sistema de Suscripciones - Backend Challenge - Cristian Vasquez

API REST para un sistema de suscripciones de alto tráfico desarrollado en **Laravel 12** con **PHP 8.5**, arquitectura hexagonal (Clean Architecture), patrón Repository + Use Cases, integración con **Redis** para optimización de lectura y procesos asíncronos mediante arquitectura guiada por eventos.

---

## 🚀 Solución Técnica

A continuación explico el enfoque utilizado para resolver el challenge paso a paso:

### Análisis de Requerimientos

- **Registro de suscripciones**: Endpoint para crear nuevas suscripciones con almacenamiento persistente y validación de datos.
- **Tareas secundarias asíncronas**: Implementación de auditoría y envío de emails sin penalizar el tiempo de respuesta al usuario.
- **Consulta de estado optimizada**: Endpoint para consultar el estado de suscripción diseñado para manejar alto tráfico de peticiones.

### Principios y Tecnologías Aplicadas

- **Arquitectura limpia**: Implementación de arquitectura hexagonal (Clean Architecture) para separación de responsabilidades.
- **Principios SOLID**: Aplicación de principios de diseño orientado a objetos para código mantenible y escalable.
- **Optimización de rendimiento**: Uso de Redis como capa de caché para reducir carga en la base de datos principal.
- **Procesos asíncronos**: Implementación de arquitectura guiada por eventos (Event-Driven Architecture) para tareas secundarias.
- **Dockerización**: Entorno completamente dockerizado con Laravel Sail para facilitar el despliegue.

### Diseño de la Solución

**Modelo de Datos:**
- **3 tablas principales**: `subscriptions`, `users`, `plans`
- Cada suscripción está asociada a un usuario y un plan específico
- **Reglas de negocio**: La fecha de expiración se calcula automáticamente (10 días después de la creación)
- El sistema valida si la suscripción está activa comparando la fecha de expiración con la fecha actual

**Estrategia de Caché:**
- Implementación de **Cache-Aside Pattern** con Redis
- TTL de 24 horas para reducir significativamente las consultas a MySQL
- Evita cuellos de botella en la base de datos principal durante picos de tráfico

**Procesos Asíncronos:**
- Al crear una suscripción, se dispara el evento `SubscriptionCreated`
- Los listeners procesan las tareas secundarias mediante jobs de Laravel Queue
- Envío de emails y logs de auditoría se ejecutan de forma asíncrona sin afectar la respuesta al usuario 

---

## 📦 Tecnologías usadas

- **Laravel 12.x** (con Laravel Sail)
- **PHP 8.5**
- **MySQL 8.4** (Base de datos principal)
- **Redis** (Cache y Queue para alto rendimiento)
- **Docker (Laravel Sail)**
- **Predis 3.3** (Cliente Redis para PHP)
- **Arquitectura Hexagonal (Clean Architecture)**
- **DDD (Domain Driven Design)**
- **Event-Driven Architecture**
- **Patrón Repository + Use Cases**
- **Value Objects**
- **Principios SOLID**
- **PSR-4**
- **VScode**
- **Postman**

---

## ⚙️ Funcionalidad

### 🎯 Endpoints REST API

#### `POST /api/subscriptions`
Crea una nueva suscripción para un usuario.

**Request Body:**
```json
{
    "user_id": 1,
    "plan_id": 1
}
```

**Response (201 Created):**
```json
{
    "path": "/api/subscriptions",
    "response": "Se ha guardado la subscripción",
    "error": null
}
```

**Características:**
- ✅ Validación de entrada mediante Form Request
- ✅ Almacenamiento persistente en MySQL
- ✅ Actualización de cache en Redis (síncrono - crítico)
- ✅ Procesos asíncronos para tareas secundarias:
  - Envío de email de confirmación (Job asíncrono)
  - Log de auditoría (Job asíncrono)
- ✅ Respuesta rápida sin penalizar por tareas secundarias

---

#### `GET /api/subscriptions/{userId}/status`
Consulta el estado de suscripción de un usuario.

**Response (200 OK):**
```json
{
    "path": "/api/subscriptions/1/status",
    "response": {
        "active": "Su subscripcion esta activa",
        "plan_id": 1,
        "expires_at": "2025-12-22 05:35:00"
    },
    "error": null
}
```

**Características:**
- ✅ **Cache-Aside Pattern**: Lee primero de Redis (ultra-rápido)
- ✅ Si no existe en cache, lee de MySQL y actualiza Redis
- ✅ Optimizado para alto tráfico (millones de consultas)
- ✅ La base de datos principal no es cuello de botella

---

## 🧱 Arquitectura

### Estructura del Proyecto

```
src/SubscriptionsContext/Subscription/
├── Domain/                          # Capa de Dominio (Lógica de Negocio)
│   ├── Events/
│   │   └── SubscriptionCreated.php # Evento de dominio
│   ├── ValueObjects/               # Objetos de valor inmutables
│   │   ├── UserId.php
│   │   ├── SubscriptionStore.php
│   │   └── SubscriptionStoreCache.php
│   ├── Repositories/                # Interfaces (Ports)
│   │   ├── SubscriptionRepositoryPort.php
│   │   └── SubscriptionCachePort.php
│   ├── Subscription.php            # Entidad de dominio
│   └── Exceptions/
│       └── SubscriptionStoreFailedException.php
│
├── Application/                     # Capa de Aplicación (Casos de Uso)
│   ├── Store/
│   │   └── SubscriptionStoreUseCase.php
│   └── Get/
│       └── SubscriptionGetStatusUseCase.php
│
└── Infrastructure/                  # Capa de Infraestructura (Implementaciones)
    ├── Controllers/
    │   ├── SubscriptionStoreController.php
    │   └── SubscriptionStatusController.php
    ├── Requests/
    │   └── SubscriptionStoreRequest.php
    ├── Repositories/
    │   ├── Eloquent/
    │   │   └── SubscriptionRepositoryAdapter.php
    │   └── Redis/
    │       └── RedisSubscriptionCacheAdapter.php
    ├── Jobs/
    │   └── Laravel/                 # Implementaciones específicas de Laravel Queue
    │       ├── SendSubscriptionEmailJob.php
    │       └── LogSubscriptionAuditJob.php
    └── Listeners/
        ├── SendSubscriptionEmailListener.php
        └── LogSubscriptionAuditListener.php
```

### Principios Arquitectónicos Aplicados

#### 1. **Arquitectura Hexagonal (Clean Architecture)**
- **Domain**: Contiene la lógica de negocio pura, sin dependencias externas
- **Application**: Orquesta los casos de uso, coordina entre Domain e Infrastructure
- **Infrastructure**: Implementaciones concretas (Laravel, Redis, MySQL)

**Ventajas:**
- ✅ Independencia de frameworks (Domain no depende de Laravel)
- ✅ Fácil testing (Domain es testeable sin infraestructura)
- ✅ Fácil migración (cambiar Redis por RabbitMQ solo afecta Infrastructure)

#### 2. **Event-Driven Architecture**
Los procesos secundarios (email, auditoría) se ejecutan de forma asíncrona mediante eventos:

```
SubscriptionStoreUseCase
    ↓ (dispara evento)
SubscriptionCreated Event
    ↓ (escuchado por)
Listeners (Infrastructure)
    ↓ (despachan)
Jobs Asíncronos (Laravel Queue)
```

**Ventajas:**
- ✅ Desacoplamiento: UseCase no conoce detalles de email/auditoría
- ✅ Escalabilidad: Jobs se procesan en cola (múltiples workers)
- ✅ Resiliencia: Reintentos automáticos (3 intentos con backoff)
- ✅ No penaliza tiempo de respuesta al usuario

#### 3. **Cache-Aside Pattern**
Estrategia de caché implementada en `SubscriptionGetStatusUseCase`:

1. **Primero**: Intenta leer de Redis (ultra-rápido, <1ms)
2. **Si no existe**: Lee de MySQL y actualiza Redis
3. **TTL**: 24 horas (configurable en `SubscriptionStoreCache`)

**Ventajas:**
- ✅ Lecturas extremadamente rápidas para alto tráfico
- ✅ La base de datos principal no es cuello de botella
- ✅ Consistencia eventual (cache se actualiza después de escrituras)

#### 4. **Patrón Repository**
Interfaces en Domain, implementaciones en Infrastructure:

- `SubscriptionRepositoryPort` → `SubscriptionRepositoryAdapter` (Eloquent)
- `SubscriptionCachePort` → `RedisSubscriptionCacheAdapter` (Redis)

**Ventajas:**
- ✅ Fácil cambio de motor de base de datos
- ✅ Testeable (puedes mockear los repositorios)
- ✅ Separación de responsabilidades

---

## 🗃 Base de Datos

### MySQL (Base de datos principal)

**Tabla: `subscriptions`**

| Campo       | Tipo        | Descripción                          |
|-------------|-------------|--------------------------------------|
| id          | bigint      | Identificador único (PK)             |
| user_id     | bigint      | ID del usuario (FK a users)         |
| plan_id     | bigint      | ID del plan (FK a plans)             |
| starts_at   | timestamp   | Fecha de inicio de la suscripción    |
| expires_at  | timestamp   | Fecha de expiración de la suscripción|
| created_at  | timestamp   | Fecha de creación                    |
| updated_at  | timestamp   | Fecha de actualización               |

**Tabla: `jobs`** (Laravel Queue)

Almacena los jobs asíncronos pendientes de procesar (email, auditoría).

---

### Redis (Cache y Queue)

**Estructura de claves:**
- `user:{userId}:subscription` → Datos de suscripción en formato JSON
- TTL: 24 horas
- Prefijo automático: `laravel-database-` (configurable)

**Uso:**
- ✅ Cache de lecturas (Cache-Aside Pattern)
- ✅ Queue para jobs asíncronos (opcional, también puede usar database queue)

---

## 🚀 Despliegue

### 🐳 Instalación y desarrollo local con Docker (Laravel Sail)

```bash
# 1. Clona el proyecto
git clone <repository-url>
cd comboplay-test-cristian-vasquez

# 2. Copia las variables de entorno
cp .env.example .env

# 3. Levanta el entorno con Docker
./vendor/bin/sail up -d

# 4. Instala dependencias
./vendor/bin/sail composer install

# 5. Genera la clave de aplicación
./vendor/bin/sail artisan key:generate

# 6. Ejecuta las migraciones
./vendor/bin/sail artisan migrate

# 7. (Opcional) Ejecuta los seeders para datos de prueba
./vendor/bin/sail artisan db:seed

# 8. Inicia el queue worker (en otra terminal)
./vendor/bin/sail artisan queue:work

# 9. La aplicación estará disponible en:
# http://localhost
```

### ⚙️ Configuración de Variables de Entorno

Asegúrate de tener estas variables en tu `.env`:

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# Redis
REDIS_CLIENT=predis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# Queue (para procesos asíncronos)
QUEUE_CONNECTION=database
# o
QUEUE_CONNECTION=redis
```
---

## 📊 Flujo de Ejecución

### Crear Suscripción (POST /api/subscriptions)

```
1. Request → SubscriptionStoreController
2. Validación → SubscriptionStoreRequest (Form Request)
3. UseCase → SubscriptionStoreUseCase
   ├─→ Guarda en MySQL (síncrono - crítico)
   ├─→ Actualiza Redis cache (síncrono - crítico)
   └─→ Dispara evento SubscriptionCreated
4. Event → EventServiceProvider registra listeners
   ├─→ SendSubscriptionEmailListener → Despacha SendSubscriptionEmailJob
   └─→ LogSubscriptionAuditListener → Despacha LogSubscriptionAuditJob
5. Jobs → Se procesan asíncronamente en cola
6. Response → 201 Created (sin esperar a que terminen los jobs)
```

**Tiempo de respuesta:** ~5-10ms (solo operaciones críticas síncronas)

---

### Consultar Estado (GET /api/subscriptions/{userId}/status)

```
1. Request → SubscriptionStatusController
2. UseCase → SubscriptionGetStatusUseCase
   ├─→ Intenta leer de Redis (Cache-Aside)
   │   └─→ Si existe: retorna inmediatamente (<1ms)
   └─→ Si no existe:
       ├─→ Lee de MySQL
       ├─→ Actualiza Redis cache
       └─→ Retorna datos
3. Response → 200 OK
```

**Tiempo de respuesta:**
- Con cache: <1ms
- Sin cache: ~5-10ms (primera consulta)

---

## 🎯 Decisiones de Arquitectura

### ¿Por qué Arquitectura Hexagonal?

**Razón:** Separación clara entre lógica de negocio e infraestructura permite:
- ✅ Cambiar de Laravel Queue a RabbitMQ sin tocar Domain
- ✅ Cambiar de Redis a Memcached sin modificar Application
- ✅ Testear Domain sin necesidad de base de datos real
- ✅ Escalabilidad y mantenibilidad a largo plazo

### ¿Por qué Cache-Aside en lugar de Write-Through?

**Razón:** 
- ✅ **Simplicidad**: Más fácil de implementar y mantener
- ✅ **Resiliencia**: Si Redis falla, el sistema sigue funcionando (lee de DB)
- ✅ **Flexibilidad**: Permite invalidar cache manualmente si es necesario
- ✅ **Adecuado para alto tráfico de lectura**: El patrón Cache-Aside es ideal cuando las lecturas superan ampliamente las escrituras

### ¿Por qué Event-Driven Architecture para procesos secundarios?

**Razón:**
- ✅ **Desacoplamiento**: El UseCase no conoce detalles de email/auditoría
- ✅ **Escalabilidad**: Múltiples workers pueden procesar jobs en paralelo
- ✅ **Resiliencia**: Reintentos automáticos si un job falla
- ✅ **No bloquea respuesta**: El usuario recibe respuesta inmediata

### ¿Por qué Jobs en Infrastructure y no en Domain?

**Razón:**
- ✅ Los Jobs dependen de Laravel (`ShouldQueue`, `Queueable`)
- ✅ Si cambias a RabbitMQ, solo creas `Infrastructure/Jobs/RabbitMQ/`
- ✅ Domain permanece independiente de frameworks
- ✅ Respeta el principio de inversión de dependencias

### ¿Por qué TTL de 24 horas en Redis?

**Razón:**
- ✅ Balance entre rendimiento y consistencia
- ✅ Las suscripciones no cambian frecuentemente
- ✅ Reduce carga en MySQL significativamente
- ✅ Configurable mediante `SubscriptionStoreCache` Value Object

---

### Probar Endpoints Manualmente

```bash
# Crear suscripción
curl -X POST http://localhost/api/subscriptions \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "plan_id": 1}'

# Consultar estado
curl http://localhost/api/subscriptions/1/status

# Probar validación (debería fallar)
curl -X POST http://localhost/api/subscriptions \
  -H "Content-Type: application/json" \
  -d '{"user_id": "invalid", "plan_id": 1}'
```

## 📚 Estructura de Respuestas

### Respuesta Exitosa

```json
{
    "path": "/api/subscriptions",
    "response": "Se ha guardado la subscripción",
    "error": null
}
```

### Respuesta con Error

```json
{
    "path": "/api/subscriptions",
    "response": null,
    "error": "El usuario ya tiene una suscripción activa"
}
```

## 👤 Autor

**Cristian Camilo Vasquez Osorio**

---

## 📄 Licencia

Este proyecto fue desarrollado como parte de un challenge técnico para comboplay.
