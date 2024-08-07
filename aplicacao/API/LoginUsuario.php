<?php 
include_once('conexao.php');

if(!isset($_SESSION)){
    session_start();
}

class LoginUsuario{
    private $mysqli;

    public function __construct($mysqli){
        $this->mysqli = $mysqli;
    }

    public function login(){
        $dados = file_get_contents("php://input");
        $dados_post = json_decode($dados,true);

        error_log(print_r($dados_post, true));

        $email_usuario = $dados_post['email'] ?? null;
        $senha_usuario = $dados_post['senha'] ?? null;

        if ($email_usuario && $senha_usuario) {
            error_log('Email: ' . $email_usuario);
            error_log('Senha: ' . $senha_usuario);
            return $this->verificarUsuario($email_usuario,$senha_usuario);
        } else {
            return json_encode(["success" => false, "message" => "Parâmetros de entrada inválidos"]);
        }
    }

    public function verificarUsuario($email_usuario,$senha_usuario){ 
        // Consulta para usuários normais
        $verificar_Email_query = "SELECT * FROM cad_normal WHERE Email = ?";
        $verificar_Email_estado = mysqli_prepare($this->mysqli,$verificar_Email_query);
        mysqli_stmt_bind_param($verificar_Email_estado,"s",$email_usuario);
        mysqli_stmt_execute($verificar_Email_estado);
        $verificar_Email_result = mysqli_stmt_get_result($verificar_Email_estado);

        // Consulta para usuários admin
        $verificar_Admin_query = "SELECT * FROM admin_users WHERE Email = ?";
        $verificar_Admin_estado = mysqli_prepare($this->mysqli,$verificar_Admin_query);
        mysqli_stmt_bind_param($verificar_Admin_estado,"s",$email_usuario);
        mysqli_stmt_execute($verificar_Admin_estado);
        $verificar_Admin_result = mysqli_stmt_get_result($verificar_Admin_estado);

        if(mysqli_num_rows($verificar_Email_result) > 0 && mysqli_num_rows($verificar_Admin_result) == 0){
            $usuario = mysqli_fetch_assoc($verificar_Email_result);

            if(!empty($senha_usuario) && password_verify($senha_usuario,$usuario['Senha'])){
                return json_encode(["success" => true, "message" => "Login bem-sucedido", "user" => $usuario]);
            }else{
                return json_encode(["success" => false, "message" => "Senha incorreta"]);
            }
        }
        elseif(mysqli_num_rows($verificar_Email_result) == 0 && mysqli_num_rows($verificar_Admin_result) > 0){
            $usuario = mysqli_fetch_assoc($verificar_Admin_result);
            if(!empty($senha_usuario) && password_verify($senha_usuario, $usuario['Senha'])){
                return json_encode(["success" => true, "message" => "Login bem-sucedido", "user" => $usuario]);
            } else {
                return json_encode(["success" => false, "message" => "Falha ao fazer login. Email ou senha incorretos."]);
            }
        } else {
            return json_encode(["success" => false, "message" => "Falha ao fazer login. Email ou senha incorretos."]);
        }
    }
}
?>