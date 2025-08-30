# 💻 Sistema de Gestão de Despesas

![Status do Projeto: Concluído](https://img.shields.io/badge/status-concluído-brightgreen)
![Linguagem](https://img.shields.io/badge/PHP-8.2-blueviolet)
![Banco de Dados](https://img.shields.io/badge/MySQL-5.7-orange)
![Front-End](https://img.shields.io/badge/Front--End-JS%2C%20CSS%2C%20HTML-blue)

Aplicação web Full-stack desenvolvida para gerenciamento de despesas pessoais e listas de compras categorizadas. 
O projeto foi construído do zero com PHP, MySQL e JavaScript, com foco em segurança, privacidade de dados por usuário e uma experiência de usuário moderna e interativa.

---

### ✨ Funcionalidades Principais

#### Back-End e Segurança:
- **Autenticação de Usuários:** Sistema completo de registro e login com gerenciamento de sessões PHP (`$_SESSION`).
- **Segurança de Senhas:** As senhas são protegidas com **hashing** (`password_hash` e `password_verify`), nunca armazenadas em texto plano.
- **Privacidade de Dados:** Arquitetura multiusuário onde cada usuário tem acesso exclusivo aos seus próprios dados (despesas, categorias e itens).
- **Gerenciamento Completo (CRUD):** Rotinas seguras para criar, ler, editar e excluir despesas e categorias de listas, utilizando prepared statements para prevenir injeção de SQL.

#### Front-End e Experiência do Usuário (UX):
- **Interface Responsiva:** O design se adapta a desktops, tablets e celulares usando CSS Flexbox e Grid.
- **Interatividade com AJAX:** Adição e exclusão de itens de listas de compras **sem recarregar a página**, proporcionando uma experiência de uso fluida e moderna.
- **Componentes Customizados:** Componentes interativos construídos com JavaScript puro, como modais, dropdowns customizados e botão de visibilidade de senha.
- **Filtro Dinâmico:** Página de histórico com um sistema de filtro por mês, que carrega por padrão os dados do mês atual para uma melhor experiência.

---

### 🖼️ Telas da Aplicação

*(Dica: Tire screenshots do seu sistema e adicione na pasta do projeto. Depois, ajuste o caminho abaixo)*

**Tela Principal (Dashboard de Listas)**
![Tela Principal](caminho/para/seu/screenshot-principal.png)

**Tela de Histórico (com filtro e tabela responsiva)**
![Tela de Histórico](caminho/para/seu/screenshot-historico.png)


---

### 🛠️ Tecnologias Utilizadas

- **Back-End:** PHP 8+
- **Front-End:** HTML5, CSS3, JavaScript (ES6+)
- **Banco de Dados:** MySQL
- **Tecnologias/APIs:** AJAX (Fetch API), DOM Manipulation, JSON
- **Ambiente Local:** XAMPP (Apache, MySQL)
- **Ferramentas:** Git, GitHub, Visual Studio Code

---

### 🚀 Como Executar o Projeto Localmente

1.  **Pré-requisitos:**
    - Ter um ambiente de servidor local como o XAMPP instalado.
    - Ter um SGBD como o MySQL ou MariaDB rodando.

2.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/erickwork04/sistema-gestao-despesas.git](https://github.com/erickwork04/sistema-gestao-despesas.git)
    ```

3.  **Mova para a pasta do servidor:**
    - Mova a pasta do projeto para o diretório `htdocs` do seu XAMPP.

4.  **Crie o Banco de Dados:**
    - Acesse o phpMyAdmin (`http://localhost/phpmyadmin`).
    - Crie um novo banco de dados chamado `gestao_despesas`.
    - Acesse a aba "SQL" e execute os comandos abaixo para criar as tabelas:

    ```sql
    CREATE TABLE `usuarios` ( `id` int(11) NOT NULL AUTO_INCREMENT, `nome` varchar(100) NOT NULL, `email` varchar(100) NOT NULL,
    `senha` varchar(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`) );
    CREATE TABLE `categorias` ( `id` int(11) NOT NULL AUTO_INCREMENT, `usuario_id` int(11) NOT NULL, `nome_categoria` varchar(255) NOT NULL, PRIMARY KEY (`id`) );
    CREATE TABLE `despesas` ( `id` int(11) NOT NULL AUTO_INCREMENT, `usuario_id` int(11) NOT NULL, `descricao` varchar(255) NOT NULL, `valor` decimal(10,2) NOT NULL,
    `data_compra` date NOT NULL, PRIMARY KEY (`id`) );
    CREATE TABLE `itens_lista` ( `id` int(11) NOT NULL AUTO_INCREMENT, `item` varchar(255) NOT NULL, `id_categoria` int(11) NOT NULL, `usuario_id` int(11) NOT NULL,
    `comprado` tinyint(1) NOT NULL DEFAULT 0, PRIMARY KEY (`id`) );
    ```

5.  **Configure a Conexão:**
    - No arquivo `conexao.php`, ajuste as credenciais do banco de dados para o seu ambiente local (para XAMPP, o padrão é usuário `root` e senha vazia).

6.  **Acesse a aplicação:**
    - Abra seu navegador e acesse `http://localhost/sistema-gestao-despesas/login.php` para criar sua conta e começar a usar.
