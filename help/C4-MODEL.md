# Documentação Arquitetural — C4 Model

Esta documentação descreve a arquitetura da **API Shopping Cart** seguindo o
[C4 Model](https://c4model.com/) de Simon Brown. O C4 organiza a visão da
arquitetura em quatro níveis de abstração, do mais macro para o mais detalhado:

| Nível | Nome | Pergunta que responde |
|-------|------|-----------------------|
| 1 | **Contexto** (System Context) | Como o sistema se encaixa no mundo? Quem usa e com o que ele conversa? |
| 2 | **Contêineres** (Containers) | Quais são as unidades executáveis/deployáveis e como elas se comunicam? |
| 3 | **Componentes** (Components) | Quais são os blocos lógicos dentro de cada contêiner? |
| 4 | **Código** (Code) | Como os componentes estão estruturados em classes? |

> Os diagramas usam [Mermaid](https://mermaid.js.org/) e são renderizados
> automaticamente pelo GitHub.

---

## Visão geral do sistema

A **API Shopping Cart** é uma API RESTful, escrita em **PHP 8.4 / Laravel 12**,
para gerenciar um carrinho de compras: cadastro de usuários, catálogo de
produtos (com categorias), itens no carrinho e fechamento de pedidos (orders).

A organização interna segue uma interpretação de **Arquitetura Hexagonal / DDD**
em camadas, com separação clara entre `Domain` (regras de negócio) e
`Infrastructure` (entrada/saída e acesso a dados). Veja
[Decisões Técnicas](./TECHNICAL-DECISIONS.md) para o racional original.

---

## Nível 1 — Diagrama de Contexto

Mostra a API como uma caixa preta, seus atores e os sistemas externos.

```mermaid
C4Context
    title Nível 1 — Contexto do Sistema (API Shopping Cart)

    Person(client, "Consumidor da API", "App mobile, SPA no browser ou outro serviço que consome a API REST via JSON")

    System(api, "API Shopping Cart", "API RESTful para gerenciar usuários, produtos, carrinho e pedidos")

    System_Ext(db, "Banco de Dados Relacional", "MySQL (dev) / PostgreSQL (prod). Persiste usuários, produtos, carrinhos e pedidos")
    System_Ext(payment, "Gateway de Pagamento", "Sistema externo de pagamento (planejado — TODO: dispatch job to payment)")

    Rel(client, api, "Faz requisições", "HTTPS/JSON")
    Rel(api, db, "Lê e grava dados", "SQL / Eloquent ORM")
    Rel(api, payment, "Inicia cobrança do pedido", "assíncrono (planejado)")

    UpdateLayoutConfig($c4ShapeInRow="3", $c4BoundaryInRow="1")
```

**Atores e sistemas externos**

- **Consumidor da API** — qualquer cliente HTTP (app mobile, front-end web, CLI,
  outro microsserviço). A camada de apresentação é JSON.
- **Banco de Dados Relacional** — persistência principal. O ambiente Docker de
  desenvolvimento usa **MySQL 8.0** (`docker-compose.yml`); a imagem de produção
  (`Dockerfile` + `railpack.toml`) é configurada para **PostgreSQL**.
- **Gateway de Pagamento** — ainda não implementado. O fluxo de `finish` do
  carrinho deixa marcado um `TODO: dispatch job to payment`, indicando o ponto de
  integração futuro.

---

## Nível 2 — Diagrama de Contêineres

Detalha as unidades deployáveis que compõem o sistema. O ambiente de
desenvolvimento (`docker-compose.yml`) é composto por três contêineres.

```mermaid
C4Container
    title Nível 2 — Contêineres (ambiente de desenvolvimento)

    Person(client, "Consumidor da API", "App / SPA / serviço")

    System_Boundary(sb, "API Shopping Cart") {
        Container(nginx, "Web Server", "Nginx", "Recebe o tráfego HTTP na porta 8080/443 e faz proxy para o PHP via FastCGI")
        Container(app, "Aplicação Laravel", "PHP 8.4 / Laravel 12", "Roteamento, controllers, regras de negócio e acesso a dados. Serve também a doc OpenAPI estática")
        ContainerDb(db, "Banco de Dados", "MySQL 8.0 / PostgreSQL", "Tabelas: users, product_categories, products, shopping_carts, orders, order_items")
    }

    System_Ext(payment, "Gateway de Pagamento", "Integração planejada")

    Rel(client, nginx, "Requisições REST", "HTTPS/JSON")
    Rel(nginx, app, "Encaminha requisição", "FastCGI :9000")
    Rel(app, db, "CRUD / transações", "PDO / Eloquent")
    Rel(app, payment, "Job de pagamento", "assíncrono (planejado)")

    UpdateLayoutConfig($c4ShapeInRow="2", $c4BoundaryInRow="1")
```

**Contêineres**

| Contêiner | Tecnologia | Responsabilidade |
|-----------|------------|------------------|
| **Web Server** | Nginx (`docker/nginx`) | Termina HTTP/HTTPS (portas `8080`/`443`) e encaminha para o PHP via FastCGI. |
| **Aplicação Laravel** | PHP 8.4 / Laravel 12 (`docker/php`) | Núcleo da API: rotas, controllers, services, repositories e models. Também serve a documentação OpenAPI estática em `public/docs/`. |
| **Banco de Dados** | MySQL 8.0 (dev) / PostgreSQL (prod) | Persistência relacional. |

> **Nota de produção:** a imagem do `Dockerfile` empacota a aplicação usando o
> servidor embutido do PHP (`php artisan serve` via `docker/start.sh`) exposto na
> porta `80`, dispensando o Nginx, e utiliza extensões `pdo_pgsql`/`pgsql`.

---

## Nível 3 — Diagrama de Componentes

Faz o "zoom" dentro do contêiner **Aplicação Laravel**, mostrando as camadas
lógicas. O fluxo de uma requisição percorre sempre o mesmo caminho:

`Rotas → Controller → Service → Repository → Model → Banco`

```mermaid
C4Component
    title Nível 3 — Componentes da Aplicação Laravel

    Person(client, "Consumidor da API")
    ContainerDb(db, "Banco de Dados", "MySQL / PostgreSQL")

    Container_Boundary(app, "Aplicação Laravel") {
        Component(routes, "Rotas da API", "routes/api.php", "Mapeia URIs HTTP para métodos dos controllers")

        Component(controllers, "Controllers", "App\\Infrastructure\\Http\\Controllers", "Recebem o Request, chamam o Service e devolvem JsonResponse")

        Component(services, "Services (Domínio)", "App\\Domain\\Service", "Regras de negócio: cálculo de total, validação de desconto, fechamento de pedido")

        Component(repoInterfaces, "Repository Interfaces", "App\\Domain\\Repository", "Contratos de acesso a dados (porta do hexágono)")

        Component(repoImpl, "Repository (Eloquent)", "App\\Infrastructure\\Repository", "Implementam as interfaces usando Eloquent (adaptador)")

        Component(models, "Models (Eloquent)", "App\\Domain\\Models", "Active Record: User, Product, ProductCategory, ShoppingCart, Order")

        Component(enums, "Enums / Supports", "App\\Infrastructure\\Supports\\Enums", "OrderStatus, ShoppingCartStatus, UserStatus, UserRole, PaymentMethod")

        Component(provider, "AppServiceProvider", "App\\Providers", "Faz o bind Interface → Implementação (DI container)")
    }

    Rel(client, routes, "HTTP/JSON")
    Rel(routes, controllers, "despacha")
    Rel(controllers, services, "invoca")
    Rel(services, repoInterfaces, "depende de")
    Rel(repoImpl, repoInterfaces, "implementa")
    Rel(provider, repoImpl, "resolve (bind)")
    Rel(services, models, "manipula")
    Rel(repoImpl, models, "consulta/persiste")
    Rel(services, enums, "usa")
    Rel(models, enums, "usa")
    Rel(repoImpl, db, "SQL", "Eloquent")

    UpdateLayoutConfig($c4ShapeInRow="2", $c4BoundaryInRow="1")
```

### Camadas (responsabilidades)

- **Rotas** (`routes/api.php`) — declaram os endpoints REST e os agrupam por
  controller/prefixo.
- **Controllers** (`App\Infrastructure\Http\Controllers`) — camada de
  apresentação: traduzem `Request`/`Response` JSON e delegam toda a lógica ao
  Service. Não contêm regras de negócio.
- **Services** (`App\Domain\Service`) — coração do software: validações,
  cálculos (ex.: total do carrinho, limite de 10% de desconto) e orquestração de
  transações (`finish`).
- **Repository Interfaces** (`App\Domain\Repository`) — portas que abstraem o
  acesso a dados; os Services dependem destas, não da implementação.
- **Repositories** (`App\Infrastructure\Repository`) — adaptadores que
  implementam as interfaces usando Eloquent.
- **Models** (`App\Domain\Models`) — entidades Eloquent (padrão Active Record).
- **Enums / Supports** — estados e tipos de domínio.
- **AppServiceProvider** — registra os binds `Interface → Implementação` no
  container de injeção de dependência do Laravel.

### Mapa de endpoints → componentes

| Prefixo | Controller | Service | Operações |
|---------|------------|---------|-----------|
| `/product-categories` | `ProductCategoryController` | `ProductCategoryService` | list, get, create, update, remove |
| `/users` | `UserController` | `UserService` | list, get, create, update, remove |
| `/products` | `ProductController` | `ProductService` | list, get, create, update, remove |
| `/users/{user}/cart-items` | `ShoppingCartItemController` | `ShoppingCartItemService` | list, get, create, update, remove |
| `/users/{user}/shopping-cart` | `ShoppingCartController` | `ShoppingCartService` | get, finish, remove |
| `/users/{user}/orders` | `OrderController` | `OrderService` | list |

---

## Nível 4 — Código

### Estrutura de pastas (mapeamento DDD)

```text
app/
├── Domain/                     # Núcleo de negócio (independente de framework)
│   ├── Models/                 # Entidades Eloquent (User, Product, ...)
│   ├── Repository/             # Interfaces (portas)
│   └── Service/                # Regras de negócio
├── Infrastructure/             # Adaptadores / detalhes técnicos
│   ├── Http/Controllers/       # Camada de apresentação (REST)
│   ├── Repository/             # Implementações Eloquent das portas
│   └── Supports/Enums/         # Enums de domínio
└── Providers/
    └── AppServiceProvider.php  # Bind das interfaces → implementações
```

### Modelo de dados (ERD)

Relacionamentos derivados das migrations em `database/migrations`.

```mermaid
erDiagram
    USERS ||--o{ SHOPPING_CARTS : "possui"
    USERS ||--o{ ORDERS : "faz"
    PRODUCT_CATEGORIES ||--o{ PRODUCTS : "classifica"
    PRODUCTS ||--o{ SHOPPING_CARTS : "está em"
    ORDERS ||--o{ ORDER_ITEMS : "contém"
    SHOPPING_CARTS ||--o{ ORDER_ITEMS : "referenciado por"

    USERS {
        uuid id PK
        string name
        string email
        json roles
        enum status
        datetime last_login
    }
    PRODUCT_CATEGORIES {
        bigint id PK
        string name
        string description
    }
    PRODUCTS {
        uuid id PK
        string name
        string description
        bigint category_id FK
        float price
        int quantity
        softdeletes deleted_at
    }
    SHOPPING_CARTS {
        uuid id PK
        uuid product_id FK
        uuid user_id FK
        int quantity
        enum status
    }
    ORDERS {
        uuid id PK
        uuid user_id FK
        float original_price
        float total_price
        float discount
        enum status
        enum payment_method
        softdeletes deleted_at
    }
    ORDER_ITEMS {
        uuid order_id PK_FK
        uuid shopping_cart_id PK_FK
    }
```

> A tabela `shopping_carts` modela tanto o carrinho quanto cada **item** do
> carrinho (uma linha por produto/quantidade). Ao fechar o pedido, as linhas em
> estado `AWAITING` são vinculadas a um `Order` pela tabela pivô `order_items` e
> têm o status atualizado para `FINISHED`.

### Fluxo dinâmico — fechamento do carrinho (`POST /users/{user}/shopping-cart/finish`)

```mermaid
sequenceDiagram
    actor C as Consumidor
    participant R as routes/api.php
    participant Ctrl as ShoppingCartController
    participant Svc as ShoppingCartService
    participant Repo as ShoppingCartItemRepository
    participant DB as Banco de Dados

    C->>R: POST /users/{user}/shopping-cart/finish { discount }
    R->>Ctrl: finish(user, request)
    Ctrl->>Svc: finish(userId, request)
    Svc->>Repo: findBy(user, status=AWAITING)
    Repo->>DB: SELECT itens do carrinho
    DB-->>Repo: itens
    Repo-->>Svc: Collection
    alt carrinho vazio
        Svc-->>Ctrl: BadRequestException("Shopping cart is empty")
        Ctrl-->>C: 400 Bad Request
    else desconto > 10% do total
        Svc-->>Ctrl: BadRequestException("discount > 10%")
        Ctrl-->>C: 400 Bad Request
    else válido
        Svc->>DB: BEGIN TRANSACTION
        Svc->>DB: INSERT order
        Svc->>DB: attach order_items
        Svc->>Repo: updateManyStatus(itens, FINISHED)
        Repo->>DB: UPDATE shopping_carts SET status=FINISHED
        Svc->>DB: COMMIT
        Note over Svc: TODO: dispatch job to payment
        Svc-->>Ctrl: Order
        Ctrl-->>C: 200 OK (JSON do pedido)
    end
```

---

## Referências

- [c4model.com](https://c4model.com/) — definição do C4 Model
- [Decisões Técnicas](./TECHNICAL-DECISIONS.md)
- [Instalação](./INSTALL.md)
- [GitHub Actions](./ACTIONS.md)
- Documentação da API (OpenAPI): `http://localhost:8080/docs/index.html`
