// src/js/script.js
const API = {
    baseURL: '/src/php',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
    }
};

document.addEventListener('DOMContentLoaded', () => {
    initModais();
    initForms();
});

async function submitForm(endpoint, data) {
    try {
        const response = await fetch(`${API.baseURL}/${endpoint}`, {
            method: 'POST',
            headers: API.headers,
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            return result;
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast(error.message, 'error');
        throw error;
    }
}

// Exemplo de uso no login
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitForm('login.php', {
        usuario: document.getElementById('loginUsuario').value,
        senha: document.getElementById('loginSenha').value
    });
});



