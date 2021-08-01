# API RESTFULL (LARAVEL, MYSQL)

<p align="center">
 <a href="#Desafio">Desafio</a> •
 <a href="#bibliotecas">bibliotecas</a> • 
<a href="#bibliotecas">Instalação</a> • 

</p>

## Desafio

Desenvolver uma API Restfull de um sistema para gerenciar projetos. Desenvolvimento Usuário
Possibilidade de cadastrar, editar, excluir e buscar usuários. Teremos 2 tipos de usuários, os
gerentes e os executores com os seguintes atributos para ambos:
<p>● Nome (obrigatório)</p>
<p>● CPF
<p>● E-mail (obrigatório)
<p>O CPF e e-mail devem ser válidos e únicos no sistema.

### Projeto
Possibilidade do gerente cadastrar, editar, excluir e buscar projetos. Os executores poderão
apenas buscar por projeto. O projeto devem ter os seguintes atributos;
<p>● Nome do projeto (obrigatório)
<p>● Prazo final (obrigatório)
<p>Além disso, o gerente deve conseguir indicar que o projeto foi concluído somente quando todas
as tarefas forem concluídas.

### Tarefas

Possibilidade do gerente cadastrar, editar, excluir e buscar tarefas que serão utilizadas no
projeto. Os executores poderão apenas buscar tarefas. A tarefa deve ter os seguintes atributos;

<p>● Título (obrigatório)
<p>● Descrição
<p>● Prazo (obrigatório)
<p>● Executor da tarefa (obrigatório)

Além disso, o executor deve conseguir indicar que a tarefa foi concluída.
É importante validar e não permitir que o campo de prazo seja maior que o prazo final do projeto.
No cadastro da tarefa o prazo final não pode ser menor que a data corrente
<!--te-->
=================
### bibliotecas

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**

# Começando

## Instalação

Por favor, verifique o guia oficial de instalação do laravel para os requisitos do servidor antes de começar. [Documentação oficial] (https://laravel.com/docs/5.4/installation#installation)

A instalação alternativa é possível sem dependências locais contando com [Docker] (# docker).

Clone o repositório

    git clone git@github.com: gothinkster / laravel-realworld-example-app.git

Mudar para a pasta repo

    cd laravel-realworld-example-app

Instale todas as dependências usando composer

    instalação do compositor

Copie o arquivo env de exemplo e faça as alterações de configuração necessárias no arquivo .env

    cp .env.example .env

Gerar uma nova chave de aplicativo

    chave artesanal php: gerar

Gere uma nova chave secreta de autenticação JWT

    php artisan jwt: generate

Execute as migrações do banco de dados (** Defina a conexão do banco de dados em .env antes de migrar **)

    php artisan migrar

Inicie o servidor de desenvolvimento local

    php artesão servir

Agora você pode acessar o servidor em http: // localhost: 8000

** TL; lista de comandos DR **

    git clone git@github.com: gothinkster / laravel-realworld-example-app.git
    cd laravel-realworld-example-app
    instalação do compositor
    cp .env.example .env
    chave artesanal php: gerar
    php artisan jwt: generate
    
** Certifique-se de definir as informações de conexão do banco de dados corretas antes de executar as migrações ** [variáveis ​​de ambiente] (# variáveis ​​de ambiente)

    php artisan migrar
    php artesão servir

## Seeding do banco de dados

** Preencher o banco de dados com os usuários

Execute o semeador de banco de dados e pronto

    php artisan db:seed

*** Nota ***: É recomendável ter um banco de dados limpo antes da propagação. Você pode atualizar suas migrações a qualquer momento para limpar o banco de dados executando o seguinte comando

    php artisan migrate:refresh
    
## Docker

Para instalar com [Docker] (https://www.docker.com), execute os seguintes comandos:

`` `
git clone https://github.com/sandrojrs/ApiFinch.git
cd lApiFinch
cp .env.example.docker .env
docker run -v $(pwd):/app composer install
cd ./docker
docker-compose up -d
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan jwt:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan db:seed
docker-compose exec php php artisan serve --host=0.0.0.0
`` `

A api pode ser acessada em [http: // localhost: 8000 / api] (http: // localhost: 8000 / api).

## Especificação de API

Este aplicativo segue as especificações da API definidas pela equipe [Thinkster] (https://github.com/gothinkster). Isso ajuda a misturar e combinar qualquer back-end com qualquer outro front-end sem conflitos.


# Visão geral do código

## Dependências

- [jwt-auth](https://github.com/tymondesigns/jwt-auth)- Para autenticação usando JSON Web Tokens

- [laravel-cors](https://github.com/barryvdh/laravel-cors) - Para lidar com Compartilhamento de recursos de origem cruzada (CORS)

- [knuckleswtf/scribe](https://github.com/knuckleswtf/scribe)- Responsavel por criar a documentação da API

- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)- Responsavel por criar os grupos e permissões dos Usúarios


## Pastas

- `app` - Contém todos os modelos do Eloquent
- `app/Http/Controllers` - Contém todos os controladores da API
- `app/services` - Contém todos os sericos resposanveis pela regras de negócio
- `app/repository` - Contém todos as interfaces do repositorios
- `app/repository/Eloquent` - Contém todos os repositorios do ORM
- `app/Http/Middleware` - Contém o middleware JWT auth
- `config` - Contém todos os arquivos de configuração do aplicativo
- `database/factories` - Contém a fábrica de modelos para todos os modelos
- `database/migrations` - Contém todas as migrações de banco de dados
- `database/seeds` - Contém o semeador de banco de dados
- `routes` - Contém todas as rotas api definidas no arquivo api.php
- `tests` - contém todos os testes de aplicação
- `tests/Feature/Api` - Contém todos os testes de API

## Variáveis ​​ambientais

- `.env` - Variáveis ​​de ambiente podem ser definidas neste arquivo

*** Nota ***: Você pode definir rapidamente as informações do banco de dados e outras variáveis ​​neste arquivo e ter o aplicativo funcionando totalmente.

----------

# API de teste

Execute o servidor de desenvolvimento laravel

     php artisan serve

A API agora pode ser acessada em

     http://localhost:8000/api

Solicitar cabeçalhos

| ** Obrigatório ** | ** Chave ** | ** Valor ** |
| ---------- | ------------------ |




