<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/fav.png" type="image/x-icon">
    <link rel="stylesheet" href="../cadastro/style_fora.css">
    <script src="../cadastro/cadastro.js"></script>
    <title>Cadastro</title>
</head>

<style>
    
</style>

<body>
    <div class="fundo">

        <div class="container">
            <div class="form1">
                <div class="voltar">
                    <a class="voltar" href="/prot_UniLife/aplicacao/login/login.html">Voltar</a>
                </div>
                <form action="../cadastro/test_cadastro.php" method="post" id="formulario">
                    <div class="form-header">
                        <div class="title">
                            <h1>Cadastre-se</h1>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-box">
                            <label for="firstname">Nome Completo</label>
                            <input id="firstname" type="text" name="name" placeholder="Nome"
                                required>
                        </div>

                        <div class="input-box">
                            <label for="cpf">CPF</label>
                            <input id="cpf" type="number" name="cpf" placeholder="Digite seu cpf " required>
                        </div>

                        <div class="input-box">
                            <label for="number">Celular</label>
                            <input id="numero" type="tel" name="celular" placeholder="(xx) xxxx-xxxx" required>
                        </div>

                        <div class="input-box">
                            <label for="email">E-mail</label>
                            <input id="email" type="email" name="email" placeholder="Digite seu e-mail" required>
                        </div>

                        <div class="input-box">
                            <label for="password">Senha</label>
                            <input style="border: 2px solid rgba(255, 255, 255, 0);" id="password" type="password" name="password" placeholder="Digite sua senha"
                                required>

                            <div class="visualizar">
                                <input  type="checkbox" name="Vsenha" id="Vsenha" onclick="visu('password')">
                                <label for="Vsenha">Visualizar</label>
                            </div>
                        </div>


                        <div class="input-box">
                            <label for="confirmPassword">Confirme sua Senha</label>
                            <input style="border: 2px solid rgba(255, 255, 255, 0);" id="confirmPassword" type="password" name="confirmPassword"
                                placeholder="Digite sua senha novamente" required>
                            <div class="visualizar">
                                <input type="checkbox" name="Vconsenha" id="Vconsenha"
                                    onclick="visu('confirmPassword')">
                                <label for="Vconsenha">Visualizar</label>
                            </div>
                        </div>

                        <div>
                            <select class="curso" name="curso">
                                <option default="" value="" disabled selected>Escolha um curso</option>
                                <?php
                                    include_once('conexao.php');
                        
                                    try{
                                        $sql = "SELECT id, nome FROM cursos ORDER BY Nome";
                                        $result = $mysqli->query($sql);
                        
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Nenhum curso disponível</option>";
                                        }
                                        $mysqli->close();
                                    }catch(Exception $e){
                                        echo"Erro ao acessar o banco de dados: ". $e->getMessage();
                                    }
                                ?>
                            </select>
                        </div>

                    </div>

                    <button type="submit" class="button2" name="cadastro">Cadastrar</button>
                </form>
            </div>
        </div>
</body>

</html>