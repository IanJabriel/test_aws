<?php 
session_start();

if(isset($_POST['submit'])){
    try{
        $codigo_usuario = $_POST['codigo_confirm'];

        if(isset($_SESSION['codigo_ver'])){
            $codigo_gerado = $_SESSION['codigo_ver'];
            echo $codigo_gerado;
        
            if($codigo_usuario == $codigo_gerado){
                echo json_encode(["sucess"=> true, "message"=>"Código de confirmação correto"]);
            }else{
                echo json_encode(["success" => false, "message" => "Código de confirmação incorreto. Por favor, verifique-o novamente."]);
            }
        }else{
            echo json_encode(["success" => false, "message" => "Código de confirmação indisponível na sessão. Por favor, gere outro código."]);
        }
    }catch(Exception $e){
        echo json_encode(["success" => false, "message" => "Ocorreu um erro ao processar a solicitação. Por favor, tente novamente mais tarde."]);
    }
}
?>