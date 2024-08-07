
<?php
include_once('conexao.php');

// API USADA NO CADASTRO DE ALUNO DE FORA DA UNIMAR

try {
    $sql = "SELECT id, nome FROM cursos ORDER BY nome";
    $result = $mysqli->query($sql);

    $cursos = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cursos[] = array(
                'id' => $row['id'],
                'nome' => $row['nome']
            );
        }
    }

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    header('Content-Type: application/json');
    echo json_encode($cursos);
    $mysqli->close();
} catch (Exception $e) {
    echo "Erro ao acessar o banco de dados: " . $e->getMessage();
}
?>
