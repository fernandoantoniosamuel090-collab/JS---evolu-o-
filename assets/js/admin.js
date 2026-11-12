(() => {
  const message = document.querySelector('#admin-message');
  let csrf = '';
  async function load() {
    const session = await fetch('/backend/public/api/auth/me.php', {credentials:'same-origin'}).then(r => r.json());
    csrf = session.csrf_token || '';
    if (!session.authenticated) { location.href = '/frontend/login.html'; return; }
    if (!session.user.roles.some(role => ['super_admin','admin'].includes(role))) {
      message.textContent = 'A sua conta não tem acesso administrativo.';
      return;
    }
    document.querySelector('#admin-user').textContent = session.user.full_name;
    const response = await fetch('/backend/public/api/admin/overview.php', {credentials:'same-origin'});
    const data = await response.json();
    if (!response.ok) throw new Error(data.error || 'Falha ao carregar indicadores.');
    document.querySelector('#metric-users').textContent = data.metrics.users;
    document.querySelector('#metric-products').textContent = data.metrics.products;
    document.querySelector('#metric-orders').textContent = data.metrics.orders;
    document.querySelector('#metric-pending').textContent = data.metrics.pending_orders;
  }
  document.querySelector('#logout-button')?.addEventListener('click', async () => {
    try {
      await fetch('/backend/public/api/auth/logout.php', {method:'POST',credentials:'same-origin',headers:{'X-CSRF-Token':csrf}});
      location.href = '/frontend/login.html';
    } catch (_) { message.textContent = 'Não foi possível terminar a sessão.'; }
  });
  load().catch(error => { message.textContent = error.message; });
})();
