# API RESTFULL (LARAVEL, MYSQL)

<p align="center">
 <a href="#Desafio">Desafio</a> •
 <a href="#Escolhas">Escolhas</a> • 
 <a href="#bibliotecas">bibliotecas</a> • 
<a href="#bibliotecas">Instalação</a> 



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
## Escolhas

  Está API seguiu os padrões de boas praticas na utilização de code clean e alguns principios SOLID.
  <p>Para exemplificar foram criados na estrutura as pastas de repository e services, visando facilitar o desenvolvimento do projeto conforme seu crescimento e para facilitar a manutenção, foram utilizados intefaces permitindo que os services possam herdar as funções basicas do eloquent, podendo ser auterado por outro ORM conforme haja necessidade sem comprometer toda a estrutura do projeto, também foram atribuidos apenas responsabilidades de requisições para os controles que fazem parte da regra de negócio, visando um codigo mais limpo e dentro das boas práticas.

<!--te-->
=================
<!--ts-->
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


Clone o repositório

    git clone https://github.com/sandrojrs/ApiFinch.git

Mudar para a pasta repo

    cd ApiFinch

Instale todas as dependências usando composer

    composer install

Copie o arquivo env de exemplo e faça as alterações de configuração necessárias no arquivo .env

    cp .env.example .env

Gerar uma nova chave de aplicativo

    php artisan key:generate

Gere uma nova chave secreta de autenticação JWT

    php artisan jwt:generate

Execute as migrações do banco de dados (** Defina a conexão do banco de dados em .env antes de migrar **)

   php artisan migrate

Inicie o servidor de desenvolvimento local

    php artisan serve

Agora você pode acessar o servidor em http://localhost:8000

** TL; lista de comandos DR **

    git clone https://github.com/sandrojrs/ApiFinch.git
    cd lApiFinch
    instalação do composer
    cp .env.example .env
    php artisan key:generate
    php artisan jwt:generate 
    
** Certifique-se de definir as informações de conexão do banco de dados corretas antes de executar as migrações ** [variáveis ​​de ambiente] (# variáveis ​​de ambiente)

    php artisan migrate

## Seeding do banco de dados

** Preencher o banco de dados com os usuários

Execute o semeador de banco de dados e pronto

    php artisan db:seed
    php artisan serve

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


| **obrigatorio** 	| **chave** 	| **Valor**            	|
|----------	|------------------	|------------------	|
| SIM      	| Content-Type     	| application/json 	|
| SIM      	| X-Requested-With 	| XMLHttpRequest   	|
| SIM 	| Authorization    	| Token {JWT}      	|

----------

# Teste case

```
Para testar se as rotas da api estão funcionando conforme o esperado rode o comando :

php artisan test

````


# Autenticação

*Observação : você deve alterar as variaveis token e url em envinoriment pela nova chave de autenticação dp JWT.
primeiro login é feito com o
     <p> * user : manager@hotmail.com
     <p> * password :12345678 </p>
*Logo após criar outros usuarios nos seguintes grupos : 
*<p>Executores(2) e Manager(1)
 
Este aplicativo usa JSON Web Token (JWT) para lidar com a autenticação. O token é passado com cada solicitação usando o cabeçalho `Authorization` com esquema` Token`. O middleware de autenticação JWT lida com a validação e autenticação do token. Verifique as seguintes fontes para saber mais sobre o JWT.
 
- https://jwt.io/introduction/
- https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html

----------

 



