import requests

API = "http://localhost:8000/api.php"

def listar():
    return requests.get(API).json()

def criar(nome, descricao=""):
    return requests.post(API, json={"nome": nome, "descricao": descricao}).json()

def atualizar(id, nome, descricao=""):
    return requests.put(f"{API}?id={id}", json={"nome": nome, "descricao": descricao}).json()

def remover(id):
    return requests.delete(f"{API}?id={id}").json()

if __name__ == "__main__":
    print("Criando...", criar("Item via Python", "Criado pelo cliente.py"))
    print("Lista:", listar())
