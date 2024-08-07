<?php

if(!isset($_SESSION)) {
    session_start();
}

/*if(!isset($_SESSION['Email'])) {
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"login.html\">Entrar</a></p>");
}*/
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Document</title>
</head>
<body>
    <!-- <h1>PAGINA HOME</h1>
    Bem vindo a home, <?php echo $_SESSION['Nome']?> -->
</body>
</html>