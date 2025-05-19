<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Cart.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Cart model
$cartModel = new Cart($db);

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : 1;

$data = [];

switch ($accion) {
    case 'get':
        echo json_encode($cartModel->getCart());
        break;

    case 'add':
        $result = $cartModel->add($id, $cantidad);
        echo json_encode($result);
        break;

    case 'update':
        $result = $cartModel->update($id, $cantidad);
        echo json_encode($result);
        break;

    case 'delete':
        $result = $cartModel->remove($id);
        echo json_encode($result);
        break;
    
    default:
        echo json_encode(['status' => false, 'error' => 'Invalid action']);
        break;
}