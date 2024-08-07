<?php 
    include_once('conexao.php');

    try{
        if(isset($_POST['RA']) && isset($_POST['senha'])){
            $RA_aluno = $_POST['RA'];
            $senha = $ $_POST['senha'];

            $verificar_RA_query = "SELECT * FROM aluno WHERE ra = ?";
            $verificar_RA_estado = mysqli_prepare($mysqli,$verificar_RA_query);
            mysqli_stmt_bind_param($verificar_RA_estado,"s",$RA_aluno);
            mysqli_stmt_execute($verificar_RA_estado);
            $verificar_RA_result = mysqli_stmt_get_result($verificar_RA_estado);

            if($verificar_RA_result === false){
                throw new Exception('', 1); 
            }

            if(mysqli_num_rows($verificar_RA_result) == 1){
                $usuario = mysqli_fetch_assoc($verificar_RA_result);

                $senha_hash = $usuario['senha'];

                if(!empty($senha) && password_verify($senha,$senha_hash)){

                    if(!isset($_SESSION)){
                        session_start();
                    }

                    $_SESSION['RA'] = $usuario['RA'];
                    $_SESSION['nome'] = $usuario['nome'];

                    header("Location: page"); //colocar a pagina inicial do unimar life
                    exit();
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
                echo"Falha ao logar! RA ou senha incorretos.";
                break;
        }
    }
?>