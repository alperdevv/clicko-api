# Clicko API - CRUD with Authentication

## Descripción

La finalidad de este README es facilitar la configuración e instalación del proyecto proporcionando una documentación clara sobre los endpoints de la API y gestión del entorno Docker.

### Funcionalidades principales:

- **API REST** con autenticación (sanctum)
- **CRUD completo** de usuarios
- **Endpoint de análisis**: top 3 dominios de email más utilizados
- **Frontend SPA** con Vue 3 y Tailwind CSS
- **Seeder** con 20 usuarios de prueba
- **Documentación** y testing con Postman

## API Endpoints

| Method | URL | Description |
| --- | --- | --- |
| GET | /api/users | Listar todos los usuarios |
| GET | /api/users/{id} | Mostrar un usuario by ID |
| POST | /api/users | Crear un nuevo usuario |
| PUT | /api/users/{id} | Actualizar un usuario by ID |
| DELETE | /api/users/{id} | Eliminar un usuario by ID |
| GET | /api/users/domains/top | Listar las 3 extensiones de dominio más usadas |

## Instalación

### Requisitos previos

- Docker + Docker Compose
- [Postman Agent](https://www.postman.com/downloads/postman-agent/)

### Clonación del repositorio

Primero, clonamos el repositorio y nos movemos al directorio:

```bash
$ git clone https://github.com/alperdevv/clicko-api.git
$ cd clicko-api
```

<br>

### Construcción y ejecución del entorno Docker

En segundo lugar, construimos y ejecutamos el entorno Docker:

```bash
$ docker-compose up -d --build
```

De esta manera, se crearán y levantarán los siguientes contenedores para el entorno de desarrollo:

- `laravelphp`: Contenedor de PHP con Laravel
- `laravelmysql`: Contenedor de MySQL para la base de datos
- `laravelpma`: Contenedor de phpMyAdmin para la gestión de la base de datos
- `laravelnginx`: Contenedor de Nginx para el servidor web
- `laravelnode`: Contenedor de Node.js para el frontend

<br>

Recuerda que puedes listarlos (para comprobar que se han levantado correctamente) con el siguiente comando:

```bash
$ docker ps
```

### Instalación y configuración de Laravel

Dentro del contenedor de PHP, instalamos las dependencias de Laravel:

```bash
$ docker exec -it laravelphp composer install
```

Preparamos el archivo para gestionar las variables de entorno:

```bash
$ docker exec -it laravelphp cp .env.example .env
```

Aclarar que el archivo .env.example ya contiene los datos necesarios para la autentación con la base de datos y similares, únicamente es necesario agregar el APP_KEY en el siguiente paso.

<br>

Por otro lado, generamos la clave de la aplicación:

```bash
$ docker exec -it laravelphp php artisan key:generate
```

Por último, ejecutamos las migraciones:

```bash
$ docker exec -it laravelphp php artisan migrate
```

<br>

### Seeders

Dentro del contenedor de PHP, ejecutamos los seeders:

```bash
$ docker exec -it laravelphp php artisan db:seed --class=UserSeeder
```

De esta manera, generaremos 20 usuarios con datos aleatorios, además del usuario base para poder interactuar con la API posteriormente (de esta manera, no tendremos que crearlo manualmente).

<br>

## Preparación del Frontend

Dentro del contenedor de Node.js, ejecutamos los siguientes comandos:

```bash
$ docker exec -it laravelnode npm install
$ docker exec -it laravelnode npm run build
```

De esta manera, nuestra aplicación, incluyendo el frontend, ya está listo para ser utilizado en el siguiente enlace (http://localhost:8080)

<br>

### Explicación de la interfaz gráfica

La interfaz gráfica es relativamente sencilla, donde en primer lugar necesitaremos iniciar sesión para generar el token correspondiente. 

Posteriormente, nos redireccionará al listado de usuarios, donde podremos ver todos los usuarios que se han creado previamente.

En esta interfaz, podremos interactuar de igual manera con los métodos previamente mencionados (Crear, Leer, Actualizar y Eliminar).


## Postman

Para facilitar el uso y el testing de la API, se proporcionan los siguientes archivos de configuración para Postman:

- `postman/workspace.postman_globals.json`
- `postman/Clicko.postman_collection`
- `postman/LaravelAPI.postman_environment`

Recuerda importar en Postman la colección mediante el archivo `postman/Clicko.postman_collection`, el entorno global mediante el archivo `postman/workspace.postman_globals.json` y el entorno de la API mediante el archivo `postman/LaravelAPI.postman_environment`.

De esta manera, tendremos disponibles todas las variables de entorno necesarias, scripts y endpoints para interactuar con la API.

**IMPORTANTE:** Es necesario seleccionar en postman el entorno `LaravelAPI` para que funcione correctamente (podrás encontrar esta opción en la parte superior en la aplicación de Postman)

<br>

Por defecto, la URL base configurada de la API es `http://localhost:8080/api`, recuerda que puedes modificarla en cualquier momento desde la variable de entorno global `baseURL`.

<br>

### Autenticación

Para poder interactuar con la API, es necesario autenticarse previamente mediante el endpoint `login`. Al haber importado la colección, ya deberíamos tener los datos del usuario base por defecto, que son los siguientes:

- Email: test@test.com
- Contraseña: 123456

En caso de no tener los datos del usuario base ya establecidos, puedes incluirlos en formato json:

```json
{
    "email": "test@test.com",
    "password": "123456"
}
```

<br>

Desde el momento en el que ejecutamos el endpoint `login`, se generará un token de autenticación que se guardará en la variable de entorno global `api_token`, que se utilizará en todas las peticiones posteriores.

De esta manera, **únicamente tendrás que preocuparte de probar los endpoints**.

<br>

### Explicación detallada de los endpoints

#### Login
- **Endpoint:** `/api/login`
- **Método:** POST
- **Descripción:** Autenticar usuario y obtener token de acceso
- **Body:**
```json
  {
    "email": "test@test.com",
    "password": "123456"
  }
```

Nos debería devolver:

```json
  {
    "message": "Login successful",
    "token": "1|abc123...",
    "user": {...},
    "status": 200
  }
```

#### Logout

- **Endpoint:** `/api/logout`
- **Método:** POST
- **Descripción:** Cerrar sesión y eliminar token de acceso
- **Headers:** Authorization: Bearer {token}

Nos debería devolver:

```json
  {
    "message": "Logged out successfully",
    "status": 200
  }
```

#### Listar todos los usuarios

- **Endpoint:**  `/api/users/`
- **Método:** GET
- **Descripción:** Listar todos los usuarios
- **Headers:** Authorization: Bearer {token}


#### Mostrar usuario específico

- **Endpoint:**  `/api/users/{id}`
- **Método:** GET
- **Descripción:** Obtener detalles de un usuario por ID
- **Headers:** Authorization: Bearer {token}

#### Crear usuario

- **Endpoint:**  `/api/users`
- **Método:** POST
- **Descripción:** Crear un nuevo usuario
- **Headers:** Authorization: Bearer {token}

#### Actualizar usuario

- **Endpoint:**  `/api/users/{id}`
- **Método:** PUT
- **Descripción:** Actualizar un usuario por ID
- **Headers:** Authorization: Bearer {token}
- **Body:**
```json
  {
    "name": "Example Name",
    "email": "example@example.com",
    "password": "password"
  }
```

#### Eliminar usuario

- **Endpoint:**  `/api/users/{id}`
- **Método:** DELETE
- **Descripción:** Eliminar un usuario por ID
- **Headers:** Authorization: Bearer {token}

#### Análisis (Top 3 extensiones de dominio)

- **Endpoint:**  `/api/users/domains/top`
- **Método:** GET
- **Descripción:** Obtener las 3 extensiones de dominio más usadas
- **Headers:** Authorization: Bearer {token}
- **Respuesta:** 
```json
  {
    "gmail.com": 8,
    "clicko.es": 5,
    "outlook.es": 3
  }
```

### Testing

#### Ejecutar Tests de API

La base de datos para los tests ya se encuentra creada previamente (testing) y configurada, por lo que no necesitaremos hacer ningún cambio. Simplemente, ejecutar el siguiente comando:

```bash
$ docker exec -it laravelphp php artisan test --filter UserApiTest
```

De esta manera, podremos asegurarnos de que los métodos de la API estén funcionando correctamente.


### Contenedor phpMyAdmin

Para la gestión de la base de datos MySQL, podemos acceder a phpMyAdmin a través de la siguiente URL:

http://localhost:80

Las credenciales de uso serán las siguientes por defecto:

- Usuario: root
- Contraseña: password

### Contenedor MySQL

De igual manera, podemos gestionar la base de datos a través del propio contenedor mediante el siguiente comando:

```bash
$ docker exec -it laravelmysql mysql -u root -p
```

Recuerda utilizar la contraseña previamente configurada (en este caso, **password**)

Aclarar que nuestra base de datos se guardará en la carpeta `db` del directorio raíz del proyecto.

<br>

### Contenedor Nginx

El contenedor ya tiene configurado el servidor web Nginx en su última versión estable para que pueda servir la aplicación Laravel.

De igual manera, puedes gestionar el archivo de configuración mediante el archivo `./.docker/nginx/app.conf`


## Troubleshooting

- Si el puerto 8080 está ocupado, puedes cambiar el puerto en docker-compose.yml y en el archivo app.conf de nginx.
- Para reiniciar completamente: `docker-compose down && docker-compose up -d --build`
- Los logs se pueden consultar con: `docker-compose logs [servicio]`


 








