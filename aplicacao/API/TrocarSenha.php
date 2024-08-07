<?php 
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");   
header('Access-Control-Allow-Methods: POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

include('conexao.php');

try{
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        class TrocarSenha{
            private $mysqli;
            private $dado;
        
            public function __construct($mysqli)
            {
                $this->mysqli = $mysqli;
            }
        
            public function alterarSenha(){
                $this->dado = json_decode(file_get_contents("php://input"),true);
        
                if($this->dado === null){
                    return['success' => false, 'message' => 'Erro ao ler os dados JSON'];
                }
        
                try{
                    $this->inserirSenhaDB();
                }catch(Exception $e){
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    return;
                }
            }
        
            private function inserirSenhaDB(){
                $nova_senha = $this->dado['new'];
                $confirm_senha = $this->dado['new_confirm'];
        
                if($nova_senha === $confirm_senha){
                    $email = $_SESSION['Email'];
        
                    $check_email = "SELECT Senha FROM cad_normal WHERE Email = ?";
                    $check_email_stmt = $this->mysqli->prepare($check_email);
                    $check_email_stmt->bind_param("s",$email);
                    $check_email_stmt->execute();
                    $check_email_stmt->store_result();
        
                    if($check_email_stmt->num_rows > 0){
                        $check_email_stmt->bind_result($senhaDB);
                        $check_email_stmt->fetch();
        
                        if(!password_verify($_POST['new'],$senhaDB)){
                            $pass_hash = password_hash($nova_senha,PASSWORD_DEFAULT);
        
                            $att_data = "UPDATE cad_normal SET Senha = ? WHERE Email = ?";
                            $att_stmt = $this->mysqli->prepare($att_data);
                            $att_stmt->bind_param("ss",$pass_hash,$email);
                            $att_stmt->execute();
                            $att_stmt->close();
        
                            echo json_encode(['sucess' => true, 'message' => 'Senha alterada com sucesso!']);
                            return;
                        }
                    }
                }else{
                    echo json_encode(['success' => false, 'message' => 'Senhas diferentes. Digite-as corretamente!']);
                    return;
                }
            } 
        }
        
        $trocadeSenha = new TrocarSenha($mysqli);
        $trocadeSenha->alterarSenha();
    }
    else{

    }
}catch(Exception $e){
    http_response_code($e->getCode());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>