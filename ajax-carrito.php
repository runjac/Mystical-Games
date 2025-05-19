<?php

session_start();
require 'conexion/conex.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : 1;

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

$data = [];

switch ($accion) {
	case 'get':
		echo json_encode($carrito);
		break;

	case 'add':

		$stmt = $pdo->prepare("SELECT * FROM games WHERE game_id = :id");
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch();

		$existe = false;
		$index = null;

		foreach($carrito as $key => $item){
			if($item['id'] == $id){
				$existe = true;
				$index = $key;
			}
		}

		if($existe){

			$cantidades = $cantidad + $carrito[$index]['cantidad'];

			if($row['stock'] >= $cantidades){

				$carrito[$index]['cantidad'] += $cantidad;
				$data['status'] = true;

			}else{
				$data['status'] = false;
				$data['error'] = 'El stock es menor a la cantidad solicitada';
			}


		}else{

			if($row['stock'] >= 1){
				$carrito[] = [
					'id' => $row['game_id'],
					'titulo' => $row['titulo'],
					'img' => $row['img'],
					'precio' => $row['precio'],
					'cantidad' => $cantidad
				];

				$data['status'] = true;
			}else{
				$data['status'] = false;
				$data['error'] = 'El stock es menor a la cantidad solicitada';
			}

			
		}

		$_SESSION['carrito'] = $carrito;

		echo json_encode($data);
		
		break;

	case 'update':

		$stmt = $pdo->prepare("SELECT * FROM games WHERE game_id = :id");
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch();

		$index = null;

		foreach($carrito as $key => $item){
			if($item['id'] == $id){
				$index = $key;
			}
		}

		if($row['stock'] >= $cantidad){

			$carrito[$index]['cantidad'] = $cantidad;
			$data['status'] = true;

		}else{
			$data['status'] = false;
			$data['error'] = 'El stock es menor a la cantidad solicitada';
		}

		$_SESSION['carrito'] = $carrito;

		echo json_encode($data);

	break;

	case 'delete':

		foreach($carrito as $key => $item){
			if($item['id'] == $id){
				array_splice($carrito, $key, 1);
			}
		}

		$_SESSION['carrito'] = $carrito;
		
		$data['status'] = true;

		echo json_encode($data);
		
		break;
	
	default:
		// code...
		break;
}