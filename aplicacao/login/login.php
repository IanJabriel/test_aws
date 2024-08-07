<?php 
    include_once('conexao.php');

    if(!isset($_SESSION)){
        session_start();
    }

    class LoginUser{
        private $mysqli;

        public function __construct($mysqli){
            $this->mysqli = $mysqli;
        }

        public function login(){
            $email_usuario = $_POST['email'];
            $senha_usuario = $_POST['senha'];

            $this->verificarUsuario($email_usuario,$senha_usuario);
        }

        public function verificarUsuario($email_usuario,$senha_usuario){ 
            // Consulta para usuarios normais
            $verificar_Email_query = "SELECT * FROM cad_normal WHERE Email = ?";
            $verificar_Email_estado = mysqli_prepare($this->mysqli,$verificar_Email_query);
            mysqli_stmt_bind_param($verificar_Email_estado,"s",$email_usuario);
            mysqli_stmt_execute($verificar_Email_estado);
            $verificar_Email_result = mysqli_stmt_get_result($verificar_Email_estado);

            // Consulta para usuarios admin
            $verificar_Admin_query = "SELECT * FROM admin_users WHERE Email = ?";
            $verificar_Admin_estado = mysqli_prepare($this->mysqli,$verificar_Admin_query);
            mysqli_stmt_bind_param($verificar_Admin_estado,"s",$email_usuario);
            mysqli_stmt_execute($verificar_Admin_estado);
            $verificar_Admin_result = mysqli_stmt_get_result($verificar_Admin_estado);

            if(mysqli_num_rows($verificar_Email_result) > 0 && mysqli_num_rows($verificar_Admin_result) == 0){
                $usuario = mysqli_fetch_assoc($verificar_Email_result);

                if(!empty($senha_usuario) && password_verify($senha_usuario,$usuario['Senha'])){

                    $_SESSION['Email'] = $usuario['Email'];
                    $_SESSION['Nome'] = $usuario['Nome_completo'];

                    if(isset($_POST['login'])){
                        header("Location: home_aluno.php");
                        exit();
                    }
                }
            }
            elseif(mysqli_num_rows($verificar_Email_result) == 0 && mysqli_num_rows($verificar_Admin_result) > 0){
                $usuario = mysqli_fetch_assoc($verificar_Admin_result);

                if(!empty($senha_usuario) && $senha_usuario === $usuario['Senha'] /*password_verify($senha_usuario,$usuario['Senha'])*/){

                    $_SESSION['Email'] = $usuario['Email'];
                    $_SESSION['Nome'] = $usuario['Nome'];

                    if(isset($_POST['login'])){
                        header("Location: home_admin.php");
                        exit();
                    }
                }
            }
            else{
                echo"Falha ao logar! Email ou senha incorretos.";
            }
        }
    }

    $login = new LoginUser($mysqli);
    $login->login();
?>