<?php
require_once("../../conexao.php");

$id = $_POST['id'];

try {
    // Verificar se o fornecedor existe
    $check = $pdo->prepare("SELECT * FROM fornecedores WHERE id = :id");
    $check->bindValue(":id", $id);
    $check->execute();
    
    if($check->rowCount() == 0) {
        echo "Fornecedor não encontrado!";
        exit();
    }

    // Verificar se existem serviços vinculados a este fornecedor
    $check_servicos = $pdo->prepare("SELECT COUNT(*) FROM servicos WHERE id = :id");
    $check_servicos->bindValue(":id", $id);
    $check_servicos->execute();
    
    if($check_servicos->fetchColumn() > 0) {
        echo "Não é possível excluir! Existem serviços vinculados a este fornecedor.";
        exit();
    }

    // Verificar se existem compras vinculadas a este fornecedor
    $check_compras = $pdo->prepare("SELECT COUNT(*) FROM compras WHERE id = :id");
    $check_compras->bindValue(":id", $id);
    $check_compras->execute();
    
    if($check_compras->fetchColumn() > 0) {
        echo "Não é possível excluir! Existem compras vinculadas a este fornecedor.";
        exit();
    }
    // Verificar se existem vendas vinculadas a este fornecedor
    $check_vendas = $pdo->prepare("SELECT COUNT(*) FROM vendas WHERE id = :id");
    $check_vendas->bindValue(":id", $id);
    $check_vendas->execute();

    if($check_vendas->fetchColumn() > 0) {
        echo "Não é possível excluir! Existem vendas vinculadas a este fornecedor.";
        exit();     
    }
    // Verificar se existem produtos vinculados a este fornecedor
    $check_produtos = $pdo->prepare("SELECT COUNT(*) FROM produtos WHERE fornecedor = :id");
    $check_produtos->bindValue(":id", $id);
    $check_produtos->execute();

    if($check_produtos->fetchColumn() > 0) {
        echo "Não é possível excluir! Existem produtos vinculados a este fornecedor.";
        exit();
    }


    // Excluir o fornecedor
    $res = $pdo->prepare("DELETE FROM fornecedores WHERE id = :id");
    $res->bindValue(":id", $id);
    $res->execute();

    echo "Excluído com Sucesso!";
    
} catch(PDOException $e) {
    echo "Erro ao excluir fornecedor: " . $e->getMessage();
}
?>
