# Instalação

Há alguns requisitos minimos para rodar a aplicação:
- git
- docker
- Linux ou WSL são preferiveis, mas não obrigatórios

> **Obs:** O tutorial a seguir foi feito pensando em ambiente Linux

## Passo a passo
### 1. **Passo 1:** Clonar o repositório

```shell
git@github.com:alessandrofeitoza/api-shopping-cart.git
```
ou
```shell
git clone https://github.com/alessandrofeitoza/api-shopping-cart
```

### 2. **Passo 2:** Subir a aplicação

Se você estiver usando ambiente Linux, basta rodar um comando no terminal, através do `make`:

```shell
make init
```

**Obs:** Esse comando vai subir preparar todo o ambiente: 
- Subir os conteineres docker (php, mysql e nginx)
- Instalar as dependencias através do composer
- Copiar o arquivo .env
- Gerar a key do Laravel
- Executar as migrations do banco de dados
- Criar alguns dados falsos para testes

### 3. **Passo 3:** Acessar a aplicação:

Agora a aplicação já deve estar rodando, e a documentação da API se encontra no endereço a seguir:
<http://localhost:8080/docs/index.html>

### 4. **Passo 4:** Subir a aplicação depois da primeira vez

Caso você já tenha subido a aplicação pelo menos uma vez, basta executar o comando abaixo para subir a aplicação:

```shell
make start
```

--- 
### Ajuda
Se você não estiver num ambiente Linux ou WSL, ou por algum motivo não possa usar o `make`, opte por fazer os comandos abaixo manualmente:

> **Obs:** Antes, entre dentro do container do PHP

```shell
cp .env.example .env

composer install

php artisan key:generate

php artisan migrate

php artisan db:seed
```




