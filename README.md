
# Setup Docker Laravel 11 com Docker e AdminLTE

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/GabrielBritto1/academiatopfitness.git "app-laravel"
```
```sh
cd "app-laravel"
```

Crie o Arquivo .env
```sh
cp .env.example .env
```
Mude o arquivo do docker-compose.yml
```sh
cp DB_CONNECTION=mysql
DB_HOST="nome que está no docker-compose"
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME="usuario do docker-compose"
DB_PASSWORD="senha do docker-compose"
```

Suba os containers do projeto
```sh
docker-compose up -d
```

Acesse o container app
```sh
docker-compose exec app bash
```

Instale as dependências do projeto
```sh
composer install && composer update
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Rodar as migrations
```sh
php artisan migrate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)
