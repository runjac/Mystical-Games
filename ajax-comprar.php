<?php

session_start();
require 'conexion/conex.php';
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$data = [];

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$pais = isset($_POST['pais']) ? $_POST['pais'] : '';
$tarjeta = isset($_POST['tarjeta']) ? $_POST['tarjeta'] : '';
$titular = isset($_POST['titular']) ? $_POST['titular'] : '';
$caducidad = isset($_POST['caducidad']) ? $_POST['caducidad'] : '';
$cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';

$usuario_id = $_SESSION['id_usuario'];
$total = 0;

foreach($carrito as $item){
	$total += $item['precio'] * $item['cantidad'];
}

$stmt = $pdo->prepare("INSERT INTO ventas (nombre, direccion, pais, tarjeta, titular, caducidad, cvv, usuario_id, total, fecha) VALUES (:nombre, :direccion, :pais, :tarjeta, :titular, :caducidad, :cvv, :usuario_id, :total, :fecha)");
$stmt->execute([
	'nombre' => $nombre,
	'direccion' => $direccion,
	'pais' => $pais,
	'tarjeta' => $tarjeta,
	'titular' => $titular,
	'caducidad' => $caducidad,
	'cvv' => $cvv,
	'usuario_id' => $usuario_id,
	'total' => $total,
	'fecha' => date('Y-m-d H:i:s')
]);

$venta_id = $pdo->lastInsertId();

foreach($carrito as $item){
	$stmt = $pdo->prepare("INSERT INTO venta_detalles (venta_id, producto_id, precio, cantidad) VALUES (:venta_id, :producto_id, :precio, :cantidad)");
	$stmt->execute([
		'venta_id' => $venta_id,
		'producto_id' => $item['id'],
		'precio' => $item['precio'],
		'cantidad' => $item['cantidad']
	]);

	$stmt = $pdo->prepare("UPDATE games SET stock = stock - :cantidad WHERE game_id = :id");
	$stmt->execute([
		'cantidad' => $item['cantidad'],
		'id' => $item['id']
	]);
}

unset($_SESSION['carrito']);

$data['status'] = true;

echo json_encode($data);