<?php
session_start();

require 'conexion/conex.php';

if(!isset($_SESSION['id_usuario'])){
	header('Location: login.php');
}
?>
Pagina para pagar...