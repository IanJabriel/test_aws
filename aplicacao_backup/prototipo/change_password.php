<?php
    include_once('conexao.php');

    if(isset($_POST['new_password'])){
        $new_password = $_POST['new_password'];

        $email_user = $_POST['email_rec'];
        $query = "SELECT senha FROM pessoafora WHERE email = ?";
        $stmt->mysqli_stmt_bind_param("s",$email_user);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();

        if(!password_verify($new_password,$password)){
            $password_cripto = password_hash($new_password,PASSWORD_DEFAULT);

            $update_query = "UPDATE pessoafora SET senha = ? WHERE email = ?";
            $update_stmt = $mysqli->prepare($update_query);
            $update_stmt->bind_param("ss", $password_cripto,$email_user);
            $update_stmt->execute();
            $update_stmt->close();
    
            echo"Senha trocada com sucesso!";
        }else{
            echo"A nova senha não pode ser igual a atual.";
        }
    }
?>