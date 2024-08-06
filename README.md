# API de Gestión de Citas Médicas

Este proyecto es una API en Laravel para la gestión de citas médicas. Permite crear, listar, actualizar y eliminar citas médicas.

## Requisitos

- PHP 8.0 o superior
- Composer
- MySQL (podría ser con Xampp)

## Instalación

Sigue estos pasos para instalar y configurar el proyecto:

1. **Clona el repositorio**

   ```bash
   git clone https://github.com/scornelio/citas-medicas-api.git
   cd citas-medicas-api
   ```

2. **Instala las dependencias de PHP**

   ```bash
   composer install
   ```

3. **Configura el archivo de entorno**

   Copia el archivo de ejemplo `.env.example` a `.env`:

   ```bash
   cp .env.example .env
   ```

   Luego, configura tus credenciales de base de datos y otras variables en el archivo `.env`.
   ```bash
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=[password]
   ```

5. **Genera la clave de aplicación**

   ```bash
   php artisan key:generate
   ```

6. **Ejecuta las migraciones**

   ```bash
   php artisan migrate
   ```

7. **Inicia el servidor de desarrollo**

   ```bash
   php artisan serve
   ```

   Por defecto, el servidor estará disponible en `http://localhost:8000`.

## Documentación de la API

La documentación de la API está disponible a través de Swagger. Para interactuar con la API desde Swagger, sigue estos pasos:

1. **Accede a la documentación de Swagger**

   La documentación de Swagger estará disponible en `http://localhost:8000/api/documentation`. 

2. **Utiliza Swagger para interactuar con la API**

   - **Autenticación**: Para autenticarte, utiliza el esquema de seguridad `api_key`. En Swagger, selecciona el botón "Authorize" en la esquina superior derecha e ingresa tu clave API en el campo "Authorization".

   - **Explora los endpoints**: Puedes ver todos los endpoints disponibles, sus parámetros y respuestas esperadas. Swagger te permitirá realizar pruebas directamente desde la interfaz.

## Ejemplos de uso

### Registrar un nuevo usuario

- **Endpoint**: `POST /api/register`
- **Descripción**: Registra un nuevo usuario.
- **Requiere autenticación**: No
- **Cuerpo de la solicitud**:

  ```json
  {
    "name": "Percy Cornelio",
    "email": "percy.cornelio@example.com",
    "password": "Password123"
  }
  ```

### Iniciar sesión

- **Endpoint**: `POST /api/login`
- **Descripción**: Inicia sesión y obtiene un token de acceso.
- **Requiere autenticación**: No
- **Cuerpo de la solicitud**:

  ```json
  {
    "email": "percy.cornelio@example.com",
    "password": "Password123"
  }
  ```

### Obtener lista de citas

- **Endpoint**: `GET /api/appointments`
- **Descripción**: Obtiene una lista de todas las citas.
- **Requiere autenticación**: Sí (clave API)
- **Encabezados**:
  - `Authorization`: `Bearer {tu_clave_api}`

### Crear una nueva cita

- **Endpoint**: `POST /api/appointments`
- **Descripción**: Crea una nueva cita.
- **Requiere autenticación**: Sí (clave API)
- **Cuerpo de la solicitud**:

  ```json
  {
    "patient_name": "Percy Cornelio",
    "doctor_name": "Rodrigo Flores",
    "appointment_date": "2024-08-06T15:00:00Z",
    "status": "scheduled"
  }
  ```

## Contribuciones

Si deseas contribuir a este proyecto, por favor sigue estos pasos:

1. **Haz un fork del repositorio**.
2. **Crea una rama para tu característica o corrección de errores**.
3. **Realiza tus cambios y haz commits**.
4. **Envía un pull request con una descripción clara de tus cambios**.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE).
