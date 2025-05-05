# 🏨 Sistema de Hotelaria para a COP30 - H-Cop

Este é um sistema de hotelaria desenvolvido para ser utilizado localmente por estabelecimentos durante a COP30. O sistema permite gerenciar quartos, hóspedes, reservas e emitir recibos em PDF.

## 🚀 Tecnologias Utilizadas

- PHP 8.x
- Laravel 12.x
- MySQL
- Docker + Docker Compose
- Nginx

## 🧱 Estrutura do Projeto

O sistema foi organizado em milestones e issues para facilitar o rastreamento das tarefas concluídas. A estrutura inclui:

- Infraestrutura com Docker (app, db, nginx)
- Sistema de autenticação simples (apenas usuário admin)
- Gerenciamento de quartos e reservas
- Visualização de disponibilidade por capacidade e vagas
- Filtros personalizados com paginação persistente
- Geração de PDF para recibos
- Interface simples e funcional

## ⚙️ Instalação (Ambiente de Desenvolvimento)

### 1. Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### 2. Clonar o repositório

```bash
git clone https://github.com/JohanGarciaO/h-cop.git
cd h-cop
```

### 3. Copiar o arquivo de ambiente

```bash
cp .env.example .env
```

### 4. Subir os containers

```bash
docker-compose up -d --build
```

### 5. Instalar as dependências

```bash
docker-compose exec app composer install
```

### 6. Gerar a chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```

### 7. Rodar as migrations

```bash
docker-compose exec app php artisan migrate
```

### 8. Popular banco com dados fictícios (opcional)

```bash
docker-compose exec app php artisan db:seed
```

### 9. Acessar o sistema

Abra no navegador: [http://localhost:8080](http://localhost:8080)

## 👤 Acesso Padrão

- **Usuário:** admin@admin.com
- **Senha:** hcop\*aroot *(ou defina conforme seu seeder)*

## 📂 Organização do Projeto

As tarefas estão descritas por milestones nas issues do repositório
Outros modelos de visualização (como Kanban board) podem ser encontrados no [Project board](https://github.com/JohanGarciaO/h-cop/projects).

## 📝 Licença

Este projeto está sob a licença MIT. Sinta-se à vontade para usar, modificar e distribuir.