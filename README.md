# CRUD Exemplo — PHP + JS + Python

CRUD de "itens" (id, nome, descrição) usando SQLite.

## Arquivos
- `api.php` — backend REST (GET/POST/PUT/DELETE) com SQLite
- `index.html` + `app.js` — frontend em JS puro
- `cliente.py` — cliente Python que consome a mesma API

## Como rodar

### 1) Subir a API PHP
```bash
cd crud-exemplo
php -S localhost:8000
```
A API fica em `http://localhost:8000/api.php`. O banco `data.db` é criado automaticamente.

### 2) Abrir o frontend
Abra `index.html` no navegador (ou sirva com `python3 -m http.server 5500`).

### 3) Usar o cliente Python
```bash
pip install requests
python3 cliente.py
```

## Endpoints
| Método | URL                       | Corpo                          |
|--------|---------------------------|--------------------------------|
| GET    | /api.php                  | —                              |
| GET    | /api.php?id=1             | —                              |
| POST   | /api.php                  | `{"nome":"x","descricao":"y"}` |
| PUT    | /api.php?id=1             | `{"nome":"x","descricao":"y"}` |
| DELETE | /api.php?id=1             | —                              |
