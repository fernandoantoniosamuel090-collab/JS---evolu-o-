(() => {
  let csrf = '';
  const message = document.querySelector('#auth-message');
  async function getCsrf() {
    const response = await fetch('/backend/public/api/auth/me.php', { credentials: 'same-origin' });
    const data = await response.json();
    csrf = data.csrf_token || '';
    return data;
  }
  const form = document.querySelector('#login-form');
  if (!form) return;
  getCsrf().then((data) => { if (data.authenticated) location.href = '/frontend/admin/'; }).catch(() => {});
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    message.textContent = 'A validar…';
    const payload = Object.fromEntries(new FormData(form).entries());
    try {
      if (!csrf) await getCsrf();
      const response = await fetch('/backend/public/api/auth/login.php', {
        method: 'POST', credentials: 'same-origin',
        headers: {'Content-Type':'application/json','X-CSRF-Token':csrf},
        body: JSON.stringify(payload)
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || 'Não foi possível iniciar sessão.');
      location.href = '/frontend/admin/';
    } catch (error) { message.textContent = error.message; }
  });
})();
