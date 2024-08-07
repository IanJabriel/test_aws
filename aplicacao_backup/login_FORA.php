<?php
    include('conexao.php');

    try {
        if(isset($_POST['email']) && isset($_POST['senha'])){
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $verificar_usuario_query = "SELECT * FROM pessoafora WHERE email = ?";
            $verificar_usuario_estado = mysqli_prepare($mysqli, $verificar_usuario_query);
            mysqli_stmt_bind_param($verificar_usuario_estado, "s", $email);
            mysqli_stmt_execute($verificar_usuario_estado);
            $verificar_usuario_result = mysqli_stmt_get_result($verificar_usuario_estado);

            if($verificar_usuario_result == false){
                throw new Exception('', 1);
            }

            if (mysqli_num_rows($verificar_usuario_result) == 1) {
                $usuario = mysqli_fetch_assoc($verificar_usuario_result);

                $senha_hash = $usuario['senha'];

                if (password_verify($senha, $senha_hash)) {
                
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                    $_SESSION['email'] = $usuario['email'];
                    $_SESSION['nome'] = $usuario['nome'];

                    header("Location: page");//definir pagina  
                    exit();
                } else {
                    throw new Exception("Falha ao logar! E-mail ou senha incorretos.");
                }
            }else{
                throw new Exception("Falha ao logar! E-mail ou senha incorretos.");
            }
        }
    }catch(Exception $e){
        switch($e->getCode()){
            case 1:
                echo"Erro na consulta de dados: ".mysqli_error($mysqli);
                break;
            case 2:
                echo "Falha ao logar! E-mail ou senha incorretos.";
                break;     
        }
    }
?>