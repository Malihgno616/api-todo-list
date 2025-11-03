# TODO LIST API

[Roadmap - Todo List Project](https://roadmap.sh/projects/todo-list-api)

# API REQUESTS URI

## REGISTER - POST

```
http://127.0.0.1:8000/api/register
```

### JSON Body
```
{
    "name": "name",
    "email": "email@email.com",
    "password": "password123"
}
```

### Response 
```
{

  "access_token": "13|jvjjro2BEst1K66fBPLfLuHA8AVQoeZox5u7dQuU3382d329",
  "token_type": "Bearer"

}

```

## LOGIN - Post

```
http://127.0.0.1:8000/api/login
```

```
{
  "email": "email@email.com",
  "password": "password"
}
```

```
{
  "access_token": "14|YqiiNkZG57Uq4j4VfJ2fK634CIdziASh1o3taxzZae9911fc",
  "token_type": "Bearer"
}
```

## LOGOUT - Post
```
http://127.0.0.1:8000/api/logout
```

```
Headers

Content-Type: application/json

Accept: application/json

Authorization: Bearer \ 14|YqiiNkZG57Uq4j4VfJ2fK634CIdziASh1o3taxzZae9911fc

```
{
  "success": true,
  "message": "Logout realizado com sucesso"
}

## Clone the repository
```
git clone https://github.com/Malihgno616/api-todo-list.git
```

## Up the docker compose
```
docker-compose up -d
```

## Copy the .env.example
```
cp .env.example .env
```

## Install all dependencies
```
composer install
```

## Generate application Key
```
php artisan key:generate
```

## Migrate databases
```
php artisan migrate
```

## Start the server
```
php artisan serve
```

