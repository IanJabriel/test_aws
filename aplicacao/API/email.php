<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");   
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('conexao.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $dados = json_decode(file_get_contents("php://input"),true);

    $email = $dados['Email'];
    $_SESSION['Email'] = $email;

    $ver_query = $mysqli->prepare("SELECT Email FROM cad_normal WHERE email = ?");
    if($ver_query){
        mysqli_stmt_bind_param($ver_query,"s",$email);
        mysqli_stmt_execute($ver_query);
        $result = $ver_query->get_result();

        if($result->num_rows > 0){
            $codigo = random_int(100000,999999);

            $_SESSION['codigo_ver'] = $codigo;

            // require_once 'C:/xampp/htdocs/pao/vendor/autoload.php';
            require_once 'C:/xampp/htdocs/prot_UniLife/vendor/autoload.php';

            function SendMail($email,$codigo){
                $mail = new PHPMailer(true);

                $mail->CharSet = 'UTF-8';
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

                $mail->isSMTP();
                $mail->SMTPAuth = true;

                $mail->Host = "sandbox.smtp.mailtrap.io";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;
                $mail->Username = "909e6d16d3f2b8";
                $mail->Password = "8b7c726a46fab6";

                $mail->setFrom('suporte_unimar_life@unimar.br', 'suporte');
                $mail->addAddress($email);

                $mail->isHtml(true);
                $mail->Subject = 'Recuperação de Senha';
                
                $mail->Body = "Seu codigo para a troca de senha: <br> <h3>$codigo</h3>";

                try{
                    $mail->send();
                    echo("Enviado com sucesso!");
                }catch(Exception $e){
                    echo ("Erro ao enviar o e-mail: {$mail->ErrorInfo}");
                }
            }

            if (SendMail($email, $codigo)) {
                echo json_encode ("Enviado com sucesso!");
            } else {
                echo json_encode("Erro ao enviar o e-mail.");
            }
        } else {
            echo json_encode("Email não registrado no banco de dados.");
        }
    }
}
?>