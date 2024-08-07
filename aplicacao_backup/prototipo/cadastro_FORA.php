<?php

    include_once('conexao.php');

    try {
        if (isset($_POST['cadastro'])) {
            $nome = $_POST['name'];
            $cpf = $_POST['cpf'];
            $email = $_POST['email'];
            $senha = $_POST['password'];
            $confirm_senha = $_POST['confirmPassword'];
            $telefone = $_POST['celular'];

            if (empty($nome) || empty($cpf) || empty($telefone) || empty($email) || empty($senha)) {
                throw new Exception('', 1);
            }

            if ($senha === $confirm_senha) {
                $senha_cripto = password_hash($senha, PASSWORD_DEFAULT);
            } else {
                throw new Exception('', 3);
            }

            $verificar_email_query = "SELECT * FROM pessoafora WHERE email = ?";
            $verificar_email_estado = mysqli_prepare($mysqli, $verificar_email_query);
            mysqli_stmt_bind_param($verificar_email_estado, "s", $email);
            mysqli_stmt_execute($verificar_email_estado);
            $verificar_email_result = mysqli_stmt_get_result($verificar_email_estado);

            if ($verificar_email_result === false) {
                throw new Exception("Erro na consulta de dados." . mysqli_error($mysqli));
            }

            if (mysqli_num_rows($verificar_email_result) > 0) {
                echo "Este e-mail já está em uso.";
            } else {
                $verificar_cpf_query = "SELECT * FROM pessoafora WHERE cpf = ?";
                $verificar_cpf_estado = mysqli_prepare($mysqli, $verificar_cpf_query);
                mysqli_stmt_bind_param($verificar_cpf_estado, "s", $cpf);
                mysqli_stmt_execute($verificar_cpf_estado);
                $verificar_cpf_result = mysqli_stmt_get_result($verificar_cpf_estado);

                if ($verificar_cpf_result === false) {
                    throw new Exception("Erro na consulta de dados." . mysqli_error($mysqli));
                }

                if (mysqli_num_rows($verificar_cpf_result) > 0) {
                    echo "Este CPF já está em uso.";
                } else {
                    $insert_query = "INSERT INTO pessoafora VALUES (?,?,?,?,?)";
                    $insert_estado = mysqli_prepare($mysqli, $insert_query);
                    mysqli_stmt_bind_param($insert_estado, "sssss", $nome, $cpf, $email, $senha_cripto, $telefone);
                    $result = mysqli_stmt_execute($insert_estado);

                    if ($result === false) {
                        throw new Exception('', 2);
                    }
                }
            }
        }
    } catch (Exception $e) {
        switch ($e->getCode()) {
            case 1:
                echo "Todos os campos são obrigatórios.";
                break;
            case 2:
                echo "Erro ao cadastrar." . mysqli_error($mysqli);
                break;
            case 3:
                echo "As senhas não estão iguais.";
                break;
        }
    }
?>