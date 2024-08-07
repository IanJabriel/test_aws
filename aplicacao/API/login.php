<?php 
// header("Content-Type: application/json");
// header("Access-Control-Allow-Origin: *");   
// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header("Access-Control-Allow-Headers: Content-Type");

// include('conexao.php');

// if($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $dados = file_get_contents("php://input");
//     $dados_post = json_decode($dados,true);
//     $email_usuario = $dados_post['email'] ?? null;
//     $senha_usuario = $dados_post['senha'] ?? null;

//     if ($email_usuario && $senha_usuario) {
//         $result = $login->login($email_usuario, $senha_usuario);
//         echo json_encode($result);
//     } else {
//         header("HTTP/1.1 400 Bad Request");
//         echo json_encode(["success" => false, "message" => "Parâmetros de entrada inválidos"]);
//     }
// }


header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include_once('LoginUsuario.php');

        $login = new LoginUsuario($mysqli);
        echo json_encode($login->login());
    } else {
        throw new Exception("Método de requisição inválido", 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>