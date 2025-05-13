<?php
require_once ("../../conexao.php");

try {
    // Verifica se a chave 'id' está definida no array $_GET
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = $pdo->prepare("DELETE FROM compras WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        echo "Excluído com sucesso!";
    } else {
        echo "ID não fornecido!";
    }
} catch (Exception $e) {
    // Handle exception and display error message
    echo "Erro ao excluir o registro: " . $e->getMessage();
}
?>
