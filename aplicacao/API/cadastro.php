<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");   
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responde com um status HTTP 200 OK
    http_response_code(200);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        include_once('CadastroUsuario.php');

        $cadastro = new CadastroUsuario($mysqli);
        echo json_encode($cadastro->cadastrar());
    } else {
        throw new Exception("Método de requisição inválido.", 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>