# Checklist de segurança antes da publicação

- [ ] Usar HTTPS obrigatório e cookies de sessão Secure.
- [ ] Garantir que o servidor executa PHP 8.2+; nunca servir ficheiros PHP como texto.
- [ ] Apontar o document root para uma raiz pública dedicada sempre que possível.
- [ ] Bloquear acesso HTTP a `.env`, `.git`, `database/`, `docs/`, `storage/` e `backend/config/`.
- [ ] Configurar variáveis DB_* no painel do alojamento, não no Git.
- [ ] Criar o primeiro superadministrador através do script CLI e remover variáveis temporárias.
- [ ] Ativar limitação de tentativas de autenticação no servidor/WAF e monitorizar abuso.
- [ ] Implementar recuperação de senha por token de uso único e verificação de e-mail.
- [ ] Rever política de CORS, CSP, cabeçalhos e política de privacidade.
- [ ] Configurar backups automáticos e testar restauro.
- [ ] Validar autorização por papel em cada endpoint e propriedade dos registos por utilizador.
- [ ] Testar registo, login, logout, CSRF, sessões, escalada de privilégios e SQL injection.
- [ ] Confirmar que logs não guardam palavras-passe, tokens, dados bancários ou conteúdo sensível.
- [ ] Confirmar que endpoints financeiros só reconhecem pagamentos após callback/webhook autenticado e verificado.
- [ ] Executar testes funcionais e de acessibilidade em Android, iOS e desktop.

O ficheiro `.htaccess` apenas ajuda em servidores Apache com mod_rewrite e mod_headers ativos. Para Nginx ou outro alojamento, reproduzir as regras no servidor correspondente. Não confiar no ficheiro como substituto de uma configuração correta do servidor.
