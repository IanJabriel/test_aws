<?php 
    include_once('conexao.php');
    try{
        if(isset($_POST['Email']) && isset($_POST['Senha'])){
            $Email_aluno = $_POST['Email'];
            $senha = $_POST['Senha'];

            $verificar_Email_query = "SELECT * FROM cad_normal WHERE Email = ?";
            $verificar_Email_estado = mysqli_prepare($mysqli,$verificar_Email_query);
            mysqli_stmt_bind_param($verificar_Email_estado,"s",$Email_aluno);
            mysqli_stmt_execute($verificar_Email_estado);
            $verificar_Email_result = mysqli_stmt_get_result($verificar_Email_estado);

            if($verificar_Email_result === false){
                throw new Exception('', 1); 
            }

            if(mysqli_num_rows($verificar_Email_result) == 1){
                $usuario = mysqli_fetch_assoc($verificar_Email_result);

                $senha_hash = $usuario['Senha'];

                if(!empty($senha) && password_verify($senha,$usuario['Senha'])){

                    if(!isset($_SESSION)){
                        session_start();
                    }

                    $_SESSION['Email'] = $usuario['Email'];
                    $_SESSION['Nome_completo'] = $usuario['Nome_completo'];

                    if(isset($_POST['login'])){
                        header("Location: melancia.php");
                        exit();
                    }

                }else{
                    throw new Exception('', 2);
                }
            }else{
                throw new Exception('', 2);
            }
        }
    }catch(Exception $e){
        switch($e->getCode()){
            case 1:
                echo "Erro na consulta de dados: ".mysqli_error($mysqli);
                break;
            case 2:
                echo"Falha ao logar! Email ou senha incorretos.";
                break;
        }
    }
?>