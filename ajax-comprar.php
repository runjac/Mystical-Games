<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Order.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Order model
$orderModel = new Order($db);

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$data = [];

if (empty($carrito)) {
    $data['status'] = false;
    $data['error'] = 'Cart is empty';
    echo json_encode($data);
    exit;
}

$orderData = [
    'nombre' => $_POST['nombre'] ?? '',
    'direccion' => $_POST['direccion'] ?? '',
    'pais' => $_POST['pais'] ?? '',
    'tarjeta' => $_POST['tarjeta'] ?? '',
    'titular' => $_POST['titular'] ?? '',
    'caducidad' => $_POST['caducidad'] ?? '',
    'cvv' => $_POST['cvv'] ?? ''
];

if ($orderModel->create($orderData, $carrito)) {
    unset($_SESSION['carrito']);
    $data['status'] = true;
} else {
    $data['status'] = false;
    $data['error'] = 'Error processing order';
}

echo json_encode($data);