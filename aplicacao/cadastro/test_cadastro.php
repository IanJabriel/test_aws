User
<?php 
    include_once("conexao.php");

    class CadastroUsuario{
        private $mysqli;

        public function __construct($mysqli) {
            $this->mysqli = $mysqli;
        }

        public function cadastrar(){
            if($this->formularioEnviado()){
                try{
                    $nome = $_POST['name'];
                    $cpf = $_POST['cpf'];
                    $email = $_POST['email'];
                    $senha = $_POST['password'];
                    $confirm_senha = $_POST['confirmPassword'];
                    $telefone = $_POST['celular'];
                    
                    if(isset($_POST['curso'])){
                        $id_curso = $_POST['curso'];
                    }else{
                        $id_curso = null;   
                    }
                    
                    if (isset($_POST['RA'])){
                        $RA = $_POST['RA'];
                    }else{
                        $RA = null;
                    }

                    $campos = [
                        'nome' => $nome,
                        'cpf' => $cpf,
                        'email' => $email,
                        'senha' => $senha,
                        'confimacaoSenha' => $confirm_senha,
                        'telefone' => $telefone,
                        'idcurso' => $id_curso,
                        'RA' => $RA
                    ];

                    $this->validarCampos($campos);

                    $senha_cripto = $this->criptografarSenha($senha);

                    $this->verificarEmail($email);
                    $this->verificarCpf($cpf);

                    $this->inserirUsuario($nome,$cpf,$email,$senha_cripto,$telefone,$id_curso,$RA);
                }catch (Exception $e){
                    $this->tratarErro($e);
                }
            }
        }

        private function formularioEnviado(){
            return isset($_POST['cadastro']);
        }

        private function validarCampos($campos){
            foreach($campos as $nome_campo => $valor_campo){
                if($valor_campo === ''){
                    throw new Exception('O campo é obrigatório.');
                }
            }
        }

        private function criptografarSenha($senha){
            if($senha === $_POST['confirmPassword']){
                return password_hash($senha,PASSWORD_DEFAULT);
            }
        }

        private function verificarEmail($email){
            $verificar_email_query = "SELECT * FROM cad_normal WHERE email = ?";
            $verificar_email_estado = mysqli_prepare($this->mysqli, $verificar_email_query);
        
            if (!$verificar_email_estado) {
                throw new Exception("Erro na preparação de consulta de e-mail: " . mysqli_error($this->mysqli));
            }
        
            mysqli_stmt_bind_param($verificar_email_estado, "s", $email);
            $result = mysqli_stmt_execute($verificar_email_estado);
        
            if ($result === false) {
                throw new Exception("Erro na execução da consulta de e-mail: " . mysqli_error($this->mysqli));
            }
        
            $verificar_email_result = mysqli_stmt_get_result($verificar_email_estado);
        
            if ($verificar_email_result === false) {
                throw new Exception("Erro na obtenção do resultado da consulta de e-mail: " . mysqli_error($this->mysqli));
            }
        
            if (mysqli_num_rows($verificar_email_result) > 0) {
                return true;
            } else {
                return false; 
            }
        }

        private function verificarCpf($cpf){
            $verificar_cpf_query = "SELECT * FROM cad_normal WHERE cpf = ?";
            $verificar_cpf_estado = mysqli_prepare($this->mysqli, $verificar_cpf_query);

            if(!$verificar_cpf_estado){
                throw new Exception("Erro na preparação da consulta de cpf: ".mysqli_error($this->mysqli));
            }

            mysqli_stmt_bind_param($verificar_cpf_estado, "s", $cpf);
            $result = mysqli_stmt_execute($verificar_cpf_estado);

            if ($result === false) {
                throw new Exception("Erro na consulta de cpf." . mysqli_error($this->mysqli));
            }

            $verificar_cpf_result = mysqli_stmt_get_result($verificar_cpf_estado);

            if($verificar_cpf_result === false){
                throw new Exception("Erro na obtenção de resultados de cpf: ".mysqli_error($this->mysqli));
            }

            if (mysqli_num_rows($verificar_cpf_result) > 0) {
                echo "Este CPF já está em uso.";
            }
        }

        private function inserirUsuario($nome, $cpf, $email, $senha_cripto, $telefone,$id_curso,$RA) {
    
            if($id_curso === null){
                $id_curso = null;
            }
            if($RA === null){
                $RA = null;
            }

            $insert_query = "INSERT INTO cad_normal (Nome_completo, CPF, Email, Senha, Telefone, idcurso, RA) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $insert_estado = mysqli_prepare($this->mysqli, $insert_query);
        
            if (!$insert_estado) {
                throw new Exception("Erro na preparação da consulta de inserção: " . mysqli_error($this->mysqli));
            }
        
            mysqli_stmt_bind_param($insert_estado, "sssssis", $nome, $cpf, $email, $senha_cripto, $telefone, $id_curso, $RA);
            $result = mysqli_stmt_execute($insert_estado);
        
            if ($result === false) {
                throw new Exception("Erro ao inserir usuário: " . mysqli_error($this->mysqli));
            }
        
            mysqli_stmt_close($insert_estado);
        }
        
        private function tratarErro(Exception $e){
            switch($e->getCode()){
                case 1:
                    echo "Erro ao cadastrar.".mysqli_error($this->mysqli);
                    break;
                default:
                echo "Erro desconhecido." .$e->getMessage();

            }
        }
    }

    $cadastro = new CadastroUsuario($mysqli);
    $cadastro->cadastrar();

    if(isset($_POST['cadastro'])){
        header("Location: /prot_UniLife/aplicacao/login/login.html");
        exit();
    }
?>