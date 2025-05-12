<?php
require_once("../../conexao.php");

if ($_POST['acao'] == 'atualizar_estoque') {
    $id_produto = $_POST['id'];
    $novo_estoque = $_POST['estoque'];
    $novo_valor_compra = $_POST['valor_compra'];
    $novo_valor_venda = $_POST['valor_venda'];
    
    try {
        $pdo->beginTransaction();
        
        $query = $pdo->prepare("UPDATE produtos SET 
                              estoque = :estoque,
                              valor_compra = :valor_compra,
                              valor_venda = :valor_venda
                              WHERE id = :id");
        
        $query->bindValue(":estoque", $novo_estoque);
        $query->bindValue(":valor_compra", $novo_valor_compra);
        $query->bindValue(":valor_venda", $novo_valor_venda);
        $query->bindValue(":id", $id_produto);
        $query->execute();
        
        $pdo->commit();
        echo "success";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>