# JS EVOLUÇÃO — arquitetura inicial

## Princípios
- Preservar o repositório existente e desenvolver em branches de trabalho.
- Frontend: HTML semântico, CSS responsivo e JavaScript sem dependências obrigatórias.
- Backend: PHP 8.2+ com PDO, endpoints JSON e validação no servidor.
- Dados: MySQL 8+, InnoDB, utf8mb4, chaves estrangeiras e índices.
- Segredos exclusivamente em variáveis de ambiente; nunca no Git.
- Não marcar pagamentos como concluídos sem confirmação do provedor.
- Não apresentar dados de demonstração como dados reais.

## Estrutura
- `/assets`: CSS, JavaScript e recursos públicos.
- `/backend/config`: configuração do backend.
- `/backend/public/api`: endpoints públicos.
- `/database`: esquema inicial e futuras migrações.
- `/docs`: decisões técnicas e procedimentos.

## Estado desta fase
- [x] Branch de fundação criada a partir de `main`.
- [x] Primeira página pública responsiva.
- [x] Navegação mobile acessível.
- [x] Manifesto PWA inicial.
- [x] Configuração PDO sem credenciais no código.
- [x] Endpoint simples de saúde da API.
- [x] Esquema inicial para utilizadores, papéis, catálogo e encomendas.
- [ ] Instalar e configurar ambiente PHP/MySQL.
- [ ] Executar testes automatizados e análise de segurança.
- [ ] Implementar autenticação, autorização e recuperação de senha.
- [ ] Implementar módulos comerciais e educativos.
- [ ] Configurar alojamento PHP/MySQL e domínio.
- [ ] Testar publicação real em dispositivos.

## Nota de implantação
Cloudflare Pages serve conteúdo estático e não executa PHP. A aplicação PHP precisa de alojamento compatível; Cloudflare pode ficar à frente como DNS, proxy e camada de proteção. O manifesto PWA será complementado com ícones e estratégia de cache após definir os recursos finais.