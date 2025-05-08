# insured_api

## Descrição

**insured_api** é a API backend desenvolvida em Laravel 19 sobre PHP 8.4.5, responsável por expor endpoints REST para gerenciar usuários segurados.  
Conta com autenticação JWT, middleware de permissão e respostas padronizadas em JSON.

---

## Funcionalidades

- **Controllers** em `app/Http/Controllers`  
  - `AuthController.php` — registro, login, logout e refresh de token.  
  - `UserController.php` — CRUD de usuários.  
  - `InsuredController.php` — CRUD de segurados.  
- **Middleware** em `app/Http/Middleware` para verificação de permissões via `AccessPermissionService`.  
- **Models** em `app/Models` para `User` e `Insured`.  
- **Services** em `app/Services`  
  - `AccessPermissionService.php` — lógica de autorização.  
  - `ApiResponse.php` — formata JSON de resposta.  
- **Rotas** em `routes/api.php`, agrupadas por middleware `auth:api` e prefixo `/api`.  
- **Migrações** e **Seeders** em `database/` para criação de tabelas e dados de teste.  
- Suporte a **CORS** e **Rate-Limiting** configurados em `config/*.php`.

---

## Pré-requisitos

- Docker ≥ 20.10  
- Docker Compose ≥ 1.29  
- (Opcional) Composer local para comandos Artisan

---

## Estrutura de Pastas

```
app/
 ├─ Http/
 │   ├─ Controllers/
 │   └─ Middleware/
 ├─ Models/
 └─ Services/
bootstrap/
config/
database/
 ├─ migrations/
 └─ seeders/
public/
resources/
routes/
tests/
vendor/
.env.example
composer.json
Dockerfile
docker-compose.yml
```

---

## Configuração

1. Copie `.env.example` para `.env` e ajuste conforme necessário:
   ```ini
   APP_NAME=insured_api
   APP_ENV=local
   APP_KEY=base64:GENERATE_WITH_php artisan key:generate
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=178.20.0.2
   DB_PORT=3306
   DB_DATABASE=insured
   DB_USERNAME=insured_user
   DB_PASSWORD=secret

   SANCTUM_STATEFUL_DOMAINS=localhost
   ```
2. Gere a chave da aplicação:
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

---

## Docker Compose

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: insured_api
    ports:
      - "8000:80"
    entrypoint:
      - sh
      - -c
      - |
        chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
        chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
        exec docker-php-entrypoint apache2-foreground
    volumes:
      - ./:/var/www/html
      - ./php.ini:/usr/local/etc/php/conf.d/php.ini
    environment:
      APP_ENV: local
      APP_DEBUG: 'true'
      APP_KEY: ${APP_KEY}
      DB_CONNECTION: mysql
      DB_HOST: 178.20.0.2
      DB_PORT: 3306
      DB_DATABASE: insured
      DB_USERNAME: insured_user
      DB_PASSWORD: secret
    depends_on:
      - db
    networks:
      insured_net:
        ipv4_address: 178.20.0.3

  db:
    image: docker.io/mysql:latest
    container_name: insured_db
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: insured
      MYSQL_USER: insured_user
      MYSQL_PASSWORD: secret
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      insured_net:
        ipv4_address: 178.20.0.2

volumes:
  dbdata:

networks:
  insured_net:
    external: true
```

---

## Comandos úteis

- **Build e suba containers**  
  ```bash
  docker-compose up -d --build
  ```
- **Instale dependências via Composer**  
  ```bash
  docker-compose exec app composer install
  ```
- **Rode migrations**  
  ```bash
  docker-compose exec app php artisan migrate
  ```
- **Gere a documentação Swagger**  
  ```bash
  docker-compose exec app php artisan l5-swagger:generate
  ```
- **Suba o servidor interno Laravel**  
  ```bash
  docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
  ```
- **Ver logs do backend**  
  ```bash
  docker-compose logs -f app
  ```
- **Executar testes**  
  ```bash
  docker-compose exec app php artisan test
  ```

---

## Endpoints principais

| Método | URL                | Descrição                      |
|:------:|--------------------|--------------------------------|
| POST   | `/api/register`    | Registrar novo usuário         |
| POST   | `/api/login`       | Autenticar e obter token JWT   |
| POST   | `/api/logout`      | Invalidar token                |
| GET    | `/api/users`       | Listar usuários (protegido)    |
| GET    | `/api/insureds`    | Listar segurados (protegido)   |
| POST   | `/api/insureds`    | Criar segurado (protegido)     |
| …      | …                  | …                              |

---

## Contribuindo

1. Faça um fork deste repositório  
2. Crie uma branch feature:
   ```bash
   git checkout -b feature/nome-da-feature
   ```
3. Commit suas alterações:
   ```bash
   git commit -m "feat: descrição da feature"
   ```
4. Abra um Pull Request  

---

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).