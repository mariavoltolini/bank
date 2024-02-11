# API Bank Pay  


Essa é uma aplicação com o objetivo de processar transações entre usuários.
A aplicação é composta por um endpoint principal de processamento de transações. E contém um endpoint auxiliar para criar usuários de teste.

 
---
## Regras de Negócio   

 

### Cadastro de usuário:  

- Existem dois tipos de usuários: regulares e comerciantes.  

- Nome Completo, CPF/CNPJ, E-mail e Senha são campos obrigatórios para ambos os tipos de usuários: usuários regulares e comerciantes.  

- O sistema não permite mais de um usuário com o mesmo CPF/CNPJ ou o mesmo endereço de E-mail.  

  

### Transferência de dinheiro:  

- Usuários podem enviar dinheiro para comerciantes ou para outros usuários.

- Os comerciantes apenas recebem transferências; eles não podem enviar dinheiro para ninguém.  

  

### Validação de saldo:  

- Antes de concluir uma transferência, o sistema valida se o usuário possui saldo suficiente em sua carteira.  

  

### Autorização Externa:  

- Antes de finalizar uma transferência, o sistema consulta um serviço de autorização externo para autorizar a transferência.  

  

### Tratamento de transações:  

 - A operação de transferência é uma transação, ou seja, é reversível em caso de alguma inconsistência e o valor devolvido à carteira do cliente.  

  

### Notificação sobre recibo de pagamento:  

- Ao processar uma transação, o sistema envia uma notificação por e-mail para o usuário recebedor através de um serviço externo.    

- Como o serviço de notificação externo pode ocasionalmente estar indisponível ou instável, o sistema armazena no banco de dados um log do status de envio para possibilitar a futura criação de um serviço de reenvio. 

  

### Serviço RESTFul:  

- O sistema é concebido como um serviço RESTFul para facilitar a comunicação e integração com sistemas externos. 

 
---
## Estrutura do projeto 

- Esse projeto segue uma abordagem MVC modificada, adaptada às necessidades de uma API REST, seguindo os princípios de separação de responsabilidades. Além disso, também segue os padrões de Service Layer e Repository Pattern. 

 

### Principais estruturas do projeto 

```

App/: Contém a lógica principal da aplicação. 

├── Console/: Armazena comandos personalizados para a interface de linha de comando Artisan. 

├── Contracts/: Contém todas as interfaces que definem os contratos para as classes em diferentes partes da aplicação. 

├── Exceptions/: Contém classes para lidar com exceções personalizadas da aplicação. 

└── HTTP/: Este diretório contém controladores, middlewares e classes de validação de requisições HTTP. 

    ├── Controllers/: Recebem as requisições HTTP da aplicação e respondem com a lógica adequada. 

    ├── Middleware/: Funcionalidades intermediárias entre as requisições HTTP e as respostas da aplicação. 

    └── Requests/: Classes que lidam com a validação das requisições HTTP. 

├── Models/: Modelos que representam as tabelas do banco de dados. 

├── Providers/: Provedores de serviços da aplicação, responsáveis por inicializar e registrar serviços durante o bootstrap da aplicação. 

├── Repositories/: Classes responsáveis pela comunicação com o banco de dados, seguindo o padrão Repository. 

└── Services/: Classes que contêm a lógica de negócios da aplicação. 

Database/: Contém arquivos relacionados ao banco de dados da aplicação. 

├── Factories/: Arquivos de fábrica usados para gerar dados fictícios durante o desenvolvimento e teste da aplicação. 

├── Migrations/: Arquivos de migração que definem a estrutura do banco de dados e as alterações nas tabelas. 

└── Seeders/: Classes usadas para popular o banco de dados com dados iniciais durante a inicialização. 

Routes/: Contém todas as definições de rotas da aplicação. 

Tests/: Armazena os testes de unidade e de integração da aplicação. 

Vendor/: Contém as dependências do projeto, gerenciadas pelo Composer. 

```

---

## Documentação 

- A documentação do projeto foi elaborada com o uso do Swagger, acessível através do seguinte caminho: "/api/documentation". 

![Swagger Documentation](public/img/swagger.png)

---
## Tecnologias

- Laravel
- PHP
- MySQL
- PHPUnit
- Swagger
- Docker

---
## Requisitos

- Docker
- Docker Compose
- Composer

---
## Instalação 

1. **Clonar o repositório**: Primeiro, você deve clonar esse repositório em sua máquina local.

2. **Configurar o arquivo `.env`**: Após o clone do repositório, gere o arquivo `.env` seguindo o padrão de `.env.example`. Certifique-se de configurar corretamente todas as variáveis necessárias no arquivo `.env` antes de prosseguir para os próximos passos.

3. **Verificar dependências do Docker e Composer**: Antes de continuar, certifique-se de ter o Docker e o Docker Compose e o Composer instalados em sua máquina. Se não estiverem instalados, siga as instruções de instalação fornecidas na documentação oficial.

4. **Executar comandos no terminal**:

    ```bash
    composer i
    docker-compose build
    ./vendor/bin/sail up
    ./vendor/bin/sail artisan migrate
    ```

    Certifique-se de executar esses comandos no diretório do projeto.

---
## Testes

- Para realizar os testes unitários e de integração, execute em seu terminal (no diretório do projeto) o comando abaixo:
```
./vendor/bin/sail artisan test 
```



