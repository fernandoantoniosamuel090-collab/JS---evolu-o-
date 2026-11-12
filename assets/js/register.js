(() => {
  const form = document.querySelector('#register-form');
  const message = document.querySelector('#register-message');
  if (!form) return;
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    message.textContent = 'A criar conta…';
    try {
      const session = await fetch('/backend/public/api/auth/me.php', {credentials:'same-origin'}).then(r => r.json());
      const payload = Object.fromEntries(new FormData(form).entries());
      const response = await fetch('/backend/public/api/auth/register.php', {
        method:'POST', credentials:'same-origin',
        headers:{'Content-Type':'application/json','X-CSRF-Token':session.csrf_token || ''},
        body:JSON.stringify(payload)
      });
      const data = await response.json();
      if (!response.ok) throw new Error(data.error || 'Não foi possível criar a conta.');
      message.textContent = 'Conta criada. A encaminhar para o início de sessão…';
      location.href = '/frontend/login.html';
    } catch (error) { message.textContent = error.message; }
  });
})();
