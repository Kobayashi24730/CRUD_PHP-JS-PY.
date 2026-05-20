<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

$db = new PDO('sqlite:' . __DIR__ . '/data.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nome TEXT NOT NULL,
  descricao TEXT
)");

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
  switch ($method) {
    case 'GET':
      if ($id) {
        $s = $db->prepare('SELECT * FROM items WHERE id=?');
        $s->execute([$id]);
        echo json_encode($s->fetch(PDO::FETCH_ASSOC) ?: null);
      } else {
        echo json_encode($db->query('SELECT * FROM items ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC));
      }
      break;

    case 'POST':
      $s = $db->prepare('INSERT INTO items (nome, descricao) VALUES (?, ?)');
      $s->execute([$input['nome'] ?? '', $input['descricao'] ?? '']);
      echo json_encode(['id' => (int)$db->lastInsertId()]);
      break;

    case 'PUT':
      if (!$id) { http_response_code(400); echo json_encode(['erro' => 'id obrigatório']); break; }
      $s = $db->prepare('UPDATE items SET nome=?, descricao=? WHERE id=?');
      $s->execute([$input['nome'] ?? '', $input['descricao'] ?? '', $id]);
      echo json_encode(['ok' => true]);
      break;

    case 'DELETE':
      if (!$id) { http_response_code(400); echo json_encode(['erro' => 'id obrigatório']); break; }
      $db->prepare('DELETE FROM items WHERE id=?')->execute([$id]);
      echo json_encode(['ok' => true]);
      break;

    default:
      http_response_code(405);
      echo json_encode(['erro' => 'método não suportado']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['erro' => $e->getMessage()]);
}
