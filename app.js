const API = 'http://localhost:8000/api.php';

const form = document.getElementById('form');
const lista = document.getElementById('lista');
const $id = document.getElementById('id');
const $nome = document.getElementById('nome');
const $desc = document.getElementById('descricao');

async function carregar() {
  try {
    const r = await fetch(API);
    const itens = await r.json();

    lista.innerHTML = itens.map(i => `
      <li>
        <div class="item-content">
          <h3>${i.nome}</h3>
          <p>${i.descricao ?? ''}</p>
        </div>
        <div class="actions">
          <button class="btn-edit" onclick="editar(${i.id}, ${JSON.stringify(i.nome)}, ${JSON.stringify(i.descricao ?? '')})">Editar</button>
          <button class="btn-delete" onclick="remover(${i.id})">Excluir</button>
        </div>
      </li>
    `).join('');
  } catch (error) {
    console.error("Erro ao carregar itens:", error);
  }
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const body = JSON.stringify({ nome: $nome.value, descricao: $desc.value });
  const headers = { 'Content-Type': 'application/json' };

  try {
    if ($id.value) {
      await fetch(`${API}?id=${$id.value}`, { method: 'PUT', headers, body });
    } else {
      await fetch(API, { method: 'POST', headers, body });
    }
    form.reset();
    $id.value = '';
    carregar();
  } catch (error) {
    console.error("Erro ao salvar item:", error);
  }
});

window.editar = (id, nome, descricao) => {
  $id.value = id;
  $nome.value = nome;
  $desc.value = descricao;
  $nome.focus();
};

window.remover = async (id) => {
  if (!confirm('Deseja realmente excluir este item?')) return;
  try {
    await fetch(`${API}?id=${id}`, { method: 'DELETE' });
    carregar();
  } catch (error) {
    console.error("Erro ao remover item:", error);
  }
};

carregar();