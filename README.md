# Banking API

Uma API bancária simples construída com Laravel.

## Requisitos

- PHP 8.2+
- Composer
- Docker (opcional)

## Instalação

### 1. Clone o repositório e instale as dependências

```bash
composer install
```

### 2. Configure o arquivo de ambiente

```bash
cp .env.example .env
```

### 3. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 4. Execute as migrations

```bash
php artisan migrate
```

## Como Rodar

### Usando o servidor nativo do Laravel

```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`

### Usando Docker

```bash
docker-compose up
```

## Estrutura do Projeto

- `app/` - Controllers, Models e Providers
- `config/` - Configurações da aplicação
- `database/` - Migrations, Factories e Seeders
- `routes/` - Definição de rotas (API e Web)
- `tests/` - Testes automatizados

## Executar Testes

```bash
php artisan test
```
