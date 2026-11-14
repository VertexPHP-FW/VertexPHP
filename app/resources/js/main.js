import { createApp } from 'vue';
import VueRoot from './vue/VueRoot.vue';

const routes = window.app?.routes ?? {};

function showError(id) {
  document.getElementById(id)?.classList.remove('d-none');
}

async function readJson(response) {
  if (!response.ok) {
    throw new Error(`La solicitud falló con HTTP ${response.status}`);
  }
  return response.json();
}

document.getElementById('login-form')?.addEventListener('submit', async (event) => {
  event.preventDefault();

  try {
    const result = await fetch(routes.loginSubmit, {
      method: 'POST',
      body: new FormData(event.currentTarget),
    }).then(readJson);

    if (result.r !== false) {
      window.location.href = routes.home;
      return;
    }

    showError('error');
  } catch (error) {
    console.error(error);
    showError('error');
  }
});

document.getElementById('register-form')?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const form = event.currentTarget;
  const password = document.getElementById('passwd');
  const confirmation = document.getElementById('passwd2');

  if (password.value !== confirmation.value) {
    window.alert('La contraseña no coincide con la confirmación.');
    confirmation.value = '';
    confirmation.focus();
    return;
  }

  try {
    const result = await fetch(routes.registerSubmit, {
      method: 'POST',
      body: new FormData(form),
    }).then(readJson);

    if (result.success !== false) {
      window.alert('La cuenta se creó correctamente. Ya puedes iniciar sesión.');
      window.location.href = routes.login;
      return;
    }

    showError('error');
  } catch (error) {
    console.error(error);
    showError('error');
  }
});

document.querySelectorAll('.toggle-passwd').forEach((button) => {
  button.addEventListener('click', () => {
    const input = document.getElementById(button.dataset.ownpasselem);
    if (input) {
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  });
});

document.getElementById('change-passwd-form')?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const form = event.currentTarget;
  const oldPassword = document.getElementById('oldpasswd');
  const newPassword = document.getElementById('newpasswd');
  const confirmation = document.getElementById('passwd2');

  try {
    const passwordData = new FormData();
    passwordData.append('password', oldPassword.value);
    const currentPassword = await fetch(routes.passwordCheck, {
      method: 'POST',
      body: passwordData,
    }).then(readJson);

    if (currentPassword.r !== true) {
      window.alert('La contraseña actual es incorrecta.');
      oldPassword.value = '';
      oldPassword.focus();
      return;
    }

    if (newPassword.value !== confirmation.value) {
      window.alert('La nueva contraseña no coincide con la confirmación.');
      confirmation.value = '';
      confirmation.focus();
      return;
    }

    const result = await fetch(routes.passwordUpdate, {
      method: 'POST',
      body: new FormData(form),
    }).then(readJson);

    if (result.success !== false) {
      window.alert('La contraseña se actualizó. Inicia sesión de nuevo.');
      window.location.href = routes.logout;
      return;
    }

    showError('error');
  } catch (error) {
    console.error(error);
    showError('error');
  }
});

document.querySelectorAll('[data-vue-app]').forEach((element) => {
  let props = {};
  try {
    props = JSON.parse(element.dataset.props || '{}');
  } catch (error) {
    console.error('Los props de Vue deben ser JSON válido.', error);
  }

  createApp(VueRoot, props).mount(element);
});
