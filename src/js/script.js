// Configuração da API
const API = {
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json'
    }
};

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    initModais();
    initForms();
    setupCSRF();
});

// Setup CSRF
function setupCSRF() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        API.headers['X-CSRF-Token'] = token;
    }
}

// Gerenciamento de Modais
function initModais() {
    const modais = document.querySelectorAll('[data-modal]');
    modais.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = link.getAttribute('data-modal');
            abrirModal(modalId);
        });
    });

    document.querySelectorAll('.modal .close').forEach(btn => {
        btn.addEventListener('click', () => fecharModal(btn.closest('.modal').id));
    });
}

// Gerenciamento de Formulários
function initForms() {
    const forms = document.querySelectorAll('form[data-api]');
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
}

// Submissão de Formulário
async function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const endpoint = form.getAttribute('data-api');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    try {
        const response = await submitForm(endpoint, data);
        if (response.success) {
            showMessage(form.id + 'Message', response.message, 'success');
            if (endpoint === 'login.php') {
                handleLoginSuccess(response);
            }
        }
    } catch (error) {
        showMessage(form.id + 'Message', error.message, 'error');
    }
}

// Envio para API
async function submitForm(endpoint, data) {
    try {
        const response = await fetch(`${API.baseURL}/${endpoint}`, {
            method: 'POST',
            headers: API.headers,
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error('Erro na requisição');
        }

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.message);
        }

        return result;
    } catch (error) {
        throw new Error(error.message || 'Erro ao processar requisição');
    }
}

// Utilitários
function showMessage(elementId, message, type) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.className = `message ${type}`;
        element.style.display = 'block';
    }
}

function abrirModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

function fecharModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

function handleLoginSuccess(response) {
    fecharModal('loginModal');
    abrirModal('bemVindoModal');
    document.getElementById('usuarioLogadoTexto').textContent = 
        `Bem-vindo, ${response.usuario.nome}!`;
}