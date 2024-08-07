<?php 
    include_once("conexao.php");

    class CadastroUsuario{
        private $mysqli;
        private $dado;

        public function __construct($mysqli) {
            $this->mysqli = $mysqli;
        }

        public function cadastrar(){
            $this->dado = json_decode(file_get_contents("php://input"),true);
            // var_dump($this->dado);

            if($this->dado === null){
                return["success" => false, "message" => "Erro ao decodificar os dados JSON"];
            }

            try{
                $this->validarCampos();
                $this->verificarEmail();
                $this->verificarCpf();
                $this->inserirUsuario();

                return ["success" => true, "message" => "Usuário cadastrado com sucesso"];
            }catch (Exception $e){
                return ["success" => false, "message" => $e->getMessage()];
            }
            
        }

        private function validarCampos(){
            $camposObrigatorios = ['name','cpf','email','password','celular'];
            foreach($camposObrigatorios as $campo){
                if(!isset($this->dado[$campo]) || empty($this->dado[$campo])){
                    throw new Exception('O campo '.$campo. ' é obrigatório.');
                }
            }
        }

        private function verificarEmail(){
            $email = $this->dado['email'];

            $query_email = "SELECT * FROM cad_normal WHERE Email=?";
            $email_estado = mysqli_prepare($this->mysqli, $query_email);
            mysqli_stmt_bind_param($email_estado, "s", $email);
            mysqli_stmt_execute($email_estado);
            mysqli_stmt_store_result($email_estado);
            $num_rows = mysqli_stmt_num_rows($email_estado);
            mysqli_stmt_close($email_estado);

            if($num_rows > 0){
                throw new Exception("Este e-mail já esta cadastrado.");
            }
        }

        private function verificarCpf(){
            $cpf = $this->dado['cpf'];

            $query_cpf = "SELECT * FROM cad_normal WHERE CPF = ?";
            $cpf_estado = mysqli_prepare($this->mysqli,$query_cpf);
            mysqli_stmt_bind_param($cpf_estado, "s", $cpf);
            mysqli_stmt_execute($cpf_estado);
            mysqli_stmt_store_result($cpf_estado);
            $num_rows = mysqli_stmt_num_rows($cpf_estado);
            mysqli_stmt_close($cpf_estado);
            
            if($num_rows > 0){
                throw new Exception("Este CPF já está cadastrado.");
            }
        }

        private function inserirUsuario() {
            $nome = $this->dado['name'];
            $cpf = $this->dado['cpf'];
            $telefone = $this->dado['celular'];
            $email = $this->dado['email'];
            $senha = password_hash($this->dado['password'],PASSWORD_DEFAULT);
            $id_curso = $this->dado['curso'];
            $RA = $this->dado['ra'];

            $insert_query = "INSERT INTO cad_normal (Nome_completo, CPF, Email, Senha, Telefone, idcurso, RA) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_estado = mysqli_prepare($this->mysqli, $insert_query);
        
            if (!$insert_estado) {
                throw new Exception("Erro na preparação da consulta de inserção: " . mysqli_error($this->mysqli));
            }
        
            mysqli_stmt_bind_param($insert_estado, "sssssis", $nome, $cpf, $email, $senha, $telefone, $id_curso, $RA);
            $result = mysqli_stmt_execute($insert_estado);
        
            if ($result === false) {
                throw new Exception("Erro ao inserir usuário: " . mysqli_error($this->mysqli));
            }
        
            mysqli_stmt_close($insert_estado);
        }
    }
?>