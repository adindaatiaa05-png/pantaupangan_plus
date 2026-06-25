<?php

// Mengambil file controller secara manual khas PHP Native
require_once __DIR__ . '/../Controllers/HarvestController.php';

$controller = new HarvestController();

// Mengambil method HTTP yang dikirim (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Mengambil ID jika dikirim melalui URL (contoh: api.php?id=5)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Routing Manual Sederhana
switch ($method) {
    case 'GET':
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;
    case 'POST':
        $controller->store();
        break;
    case 'PUT':
    case 'PATCH':
        if ($id) {
            $controller->update($id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID data harus disertakan untuk update']);
        }
        break;
    case 'DELETE':
        if ($id) {
            $controller->destroy($id);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID data harus disertakan untuk menghapus']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method tidak diizinkan']);
        break;
}