# API Shopping Cart
Api RESTful para gerenciamento de um carrinho de compras

### Tecnologias
- Docker
- PHP 8.4
- Laravel
- MySQL
- PHPUnit

### Arquitetura e System Design
Todo o design das camadas da aplicação foram pensados de acordo com as boas práticas do ecossistema web, através da interpretação do autor (@alessandrofeitoza) sobre "Arquitetura Hexagonal", "DDD", "entre outras coisas"

##### Arquitetura em Camadas
A arquitetura definida será baseada em camadas, dando uma melhor proposta de escalabilidade para a aplicação, como demonstra a figura a seguir:

> **Obs:** No diagrama abaixo teremos uma visão a longo prazo da aplicação, porém, para esta utilizaremos como camada de apresentação o JSON, ou seja, uma API REST.
> E para o acesso aos dados, utilizaremos prioritariamente um banco de dados SQL Relacional.

```mermaid
flowchart TD
    style A color:#FFF, fill:#AA00FF
    style B color:#FFF, fill:#990000
    style C color:#FFF, fill:#3d85c6

    C[[Camada de Apresentação]]

    D(fa:fa-computer CLI) <-->|text| C
    E(fa:fa-mobile App) <-->|JSON| C
    F(fa:fa-globe Browser) <-->|HTML/CSS/JS| C
    C <--> B
    B[[Camada Lógica]] <--> A[[Acesso aos Dados]]
    A <--> S(fa:fa-database SQL)
    A <--> N(fa:fa-file NoSQL)
    A <--> St(fa:fa-hard-drive Storage)
```

##### Arquitetura Hexagonal / Domain-drive Design

**Definição das Camadas**

```mermaid
flowchart TD
    HC((HttpClient)) --JsonRequest<--> R[Routes]
    R --> CA[[Controller]]
    CA <--> S
    CA --> ES
    ES(EventSubscriber) --JsonResponse--> HC
    subgraph one[Domain]
        S[Service]
        S <--> RP[Repository]
        RP <--schema--> E((Model))
    end
    RP <==ORM/Eloquent==> D[(Database)]
```

O fluxograma acima demonstra a forma como as camadas vão servir a aplicação.

- **Repository:** Camada responsável por definir o acesso ao banco, embora o projeto seja Laravel, a ideia aqui é centralizar todo o acesso aos dados nessa camada;
- **Service:** Camada responsável pela lógica de negócio, calculos, e outras coisas especificas do "coração do software";
- **Controller:** Camada responsável por utilizar o "Service" para fins de entrada e saída dos dados, através de JSON Requests e Responses;
- **Model:** Por se tratar de Eloquent, a abordagem usada aqui é "ActiveRecord", ou seja, modelar os dados do banco apartir de classes do PHP;

> **Obs:** O "Dominio" está bem definido, sendo apenas "Model", "Repository" e "Service" as principais camadas nesse setor.

### Estilo de código
O estilo de código do projeto segue principalmente a `PSR-12`, que é a recomendação padrão da linguagem PHP para o estilo de código.

> Outras abordagens utilizadas estão definidas no `/.php-cs-fixer.php`

### Comandos úteis
Há um arquivo Makefile com "atalhos" para executar comandos dentro dos conteineres sem precisar entrar neles
