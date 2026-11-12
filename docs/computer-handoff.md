# Entrega para continuar no computador

## Branch de trabalho
`foundation/phase-1`

## Clonar
```bash
git clone -b foundation/phase-1 https://github.com/fernandoantoniosamuel090-collab/JS---evolu-o-.git
cd JS---evolu-o-
```

## Ambiente necessário
- VS Code
- PHP 8.2+ com PDO MySQL e mbstring
- MySQL 8+
- Servidor web Apache ou Nginx configurado para PHP

## Preparação da base de dados
1. Criar uma base de dados MySQL.
2. Importar `database/schema.sql`.
3. Configurar as variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS` no ambiente do servidor.
4. Executar o script CLI de criação do primeiro administrador, com as variáveis ADMIN_* definidas apenas temporariamente no terminal:
   - ADMIN_EMAIL
   - ADMIN_NAME
   - ADMIN_PASSWORD (mínimo 16 caracteres)
   - executar `php backend/bin/create_super_admin.php`
5. Apagar as variáveis temporárias do terminal depois da execução.

## Testes locais iniciais
- Abrir `/` e verificar navegação mobile.
- Abrir `/backend/public/api/health.php`; esperar JSON `ok: true`.
- Abrir `/frontend/register.html`, criar utilizador de teste e confirmar que recebe apenas role customer.
- Entrar em `/frontend/login.html`.
- Entrar no painel `/frontend/admin/` com o superadministrador.
- Confirmar que cliente comum recebe HTTP 403 no endpoint `/backend/public/api/admin/overview.php`.
- Terminar sessão e confirmar redirecionamento para login.

## Limitações conhecidas antes da produção
- Não foi possível executar PHP/MySQL ou browser E2E neste ambiente de edição GitHub.
- O backend precisa de alojamento PHP real; Cloudflare Pages sozinho não executa PHP.
- Configurar HTTPS obrigatório, limites de tentativas de login no servidor/WAF, recuperação de senha por e-mail, verificação de e-mail, política de privacidade, backups e monitorização.
- O service worker guarda apenas recursos estáticos; nunca guarda respostas da API.
- A aplicação ainda não é um sistema pronto para produção.
