<?php
require_once("../../conexao.php");

$id = $_POST['id'];

try {
    // Buscar o fornecedor antes de excluir
    $query = $pdo->prepare("SELECT cpf FROM fornecedores WHERE id = :id");
    $query->bindValue(":id", $id);
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    
    if(count($res) > 0) {
        // Excluir o fornecedor
        $delete = $pdo->prepare("DELETE FROM fornecedores WHERE id = :id");
        $delete->bindValue(":id", $id);
        $delete->execute();
        
        echo "Excluído com Sucesso!";
    } else {
        echo "Fornecedor não encontrado!";
    }
} catch(PDOException $e) {
    echo "Erro ao excluir: " . $e->getMessage();
}
?>
