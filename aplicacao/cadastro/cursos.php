<?php
    include_once('conexao.php');
    $sql = "SELECT id, nome FROM cursos ORDER BY Nome";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
        }
    }

    header('')
?>