<?php
require_once("../../conexao.php");

$id = $_POST['id'];

try {
    // Verificar se existem produtos vinculados a esta categoria
    $check = $pdo->prepare("SELECT COUNT(*) FROM produtos WHERE categoria = :id");
    $check->bindValue(":id", $id);
    $check->execute();
    
    if($check->fetchColumn() > 0) {
        echo "Não é possível excluir! Existem produtos cadastrados nesta categoria.";
        exit();
    }

    // Excluir a categoria
    $res = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
    $res->bindValue(":id", $id);
    $res->execute();

    echo "Excluído com Sucesso!";
    
} catch(PDOException $e) {
    echo "Erro ao excluir categoria: " . $e->getMessage();
}
?>
