# JS EVOLUÇÃO

Ecossistema digital empresarial em desenvolvimento.

## Tecnologias
- Frontend: HTML5, CSS3 e JavaScript.
- Backend: PHP 8.2+ com PDO.
- Base de dados: MySQL 8+.
- Código e versões: GitHub.

## Estrutura disponível
- Website público: `/index.html`
- Login: `/frontend/login.html`
- Painel inicial: `/frontend/admin/`
- API: `/backend/public/api/`
- Configuração PDO: `/backend/config/database.php`
- Esquema MySQL: `/database/schema.sql`
- Documentação: `/docs/architecture.md`

## Preparar no computador
1. Instalar PHP 8.2+ com extensões `pdo_mysql` e `mbstring`, MySQL 8+ e VS Code.
2. Clonar esta branch: `git clone -b foundation/phase-1 https://github.com/fernandoantoniosamuel090-collab/JS---evolu-o-.git`
3. Criar a base de dados e importar `database/schema.sql`.
4. Configurar no ambiente do servidor: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.
5. Configurar o servidor web para PHP e proteger diretórios internos. Não disponibilizar `.env`, `.git`, `database/`, `docs/`, `storage/` ou `backend/config/` por HTTP.
6. Abrir o website e testar os endpoints, sessões, CSRF e permissões.

## Primeiro superadministrador
O registo público cria apenas utilizadores com papel `customer`. Não existe conta de administrador padrão. Criar o primeiro superadministrador diretamente no servidor com `password_hash()` e atribuir o papel `super_admin`. Nunca guardar palavras-passe ou tokens no repositório.

## Estado real
A fundação de interface, esquema inicial, endpoints iniciais de autenticação e painel administrativo foi adicionada. Ainda não foi executada num servidor PHP/MySQL nesta fase, não passou por testes completos de segurança e não está publicada em produção. Pagamentos, IA, CRM/ERP completos, cursos, mensagens e restantes módulos continuam por implementar.
