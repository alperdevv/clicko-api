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

De esta manera, se creararán y levantarán los siguientes contenedores para el entorno de desarrollo:

- `laravelphp`: Contenedor de PHP con Laravel
- `laravelmysql`: Contenedor de MySQL para la base de datos
- `laravelpma`: Contenedor de phpMyAdmin para la gestión de la base de datos
- `laravelnginx`: Contenedor de Nginx para el servidor web
- `laravelnode`: Contenedor de Node.js para el frontend

<br>

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

Con los pasos previamente realizados, el entorno de Laravel ya está listo para ser utilizado (http://localhost:8080)

<br>

### Seeders

Dentro del contenedor de PHP, ejecutamos los seeders:

```bash
$ docker exec -it laravelphp php artisan db:seed --class=UserSeeder
```

De esta manera, generaremos 20 usuarios con datos aleatorios, además del usuario base para poder interactuar con la API posteriormente (de esta manera, no tendremos que crearlo manualmente).

<br>


### Postman

Para facilitar el uso y el testing de la API, se proporcionan los siguientes archivos de configuración para Postman:

- `postman/workspace.postman_globals.json`
- `postman/Clicko.postman_collection`
- `postman/LaravelAPI.postman_environment`

Recuerda importar en Postman la colección mediante el archivo `postman/Clicko.postman_collection`, el entorno global mediante el archivo `postman/workspace.postman_globals.json` y el entorno de la API mediante el archivo `postman/LaravelAPI.postman_environment`.

De esta manera, tendremos disponibles todas las variables de entorno necesarias, scripts y endpoints para interactuar con la API.

<br>

Por defecto, la URL base configurada de la API es `http://localhost:8080/api`, recuerda que puedes modificarla en cualquier momento desde la variable de entorno global `baseURL`.

<br>




### phpMyAdmin

Para la gestión de la base de datos MySQL, podemos acceder a phpMyAdmin a través de la siguiente URL:

http://localhost:80

Las credenciales de uso serán las siguientes por defecto:

- Usuario: root
- Contraseña: password

### MySQL

De igual manera, podemos gestionar la base de datos a través del propio contenedor mediante el siguiente comando:

```bash
$ docker exec -it laravelmysql mysql -u root -p
```

Recuerda utilizar la contraseña previamente configurada (en este caso, **password**)

<br>

 








