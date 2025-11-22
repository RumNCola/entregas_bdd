<?php
session_start();
require_once 'utils.php';
$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'];

$bdd = conectarBD();

$query 