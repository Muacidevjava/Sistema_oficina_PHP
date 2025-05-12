<?php
session_start();
require_once("../../conexao.php");

if ($_POST['acao'] == 'adicionar_pedido') {
    $produtos = $_POST['produtos'];
    
    try {
        $pdo->beginTransaction();
        
        foreach ($produtos as $produto) {
            $id_produto = $produto['id'];
            $quantidade = $produto['quantidade'];
            
            // Atualiza o estoque
            $query = $pdo->prepare("UPDATE produtos SET estoque = estoque + :quantidade WHERE id = :id");
            $query->bindValue(":quantidade", $quantidade);
            $query->bindValue(":id", $id_produto);
            $query->execute();
            
            // Insere o registro do pedido
            $query = $pdo->prepare("INSERT INTO pedidos (produto, quantidade, data_pedido) VALUES (:produto, :quantidade, NOW())");
            $query->bindValue(":produto", $id_produto);
            $query->bindValue(":quantidade", $quantidade);
            $query->execute();
        }
        
        $pdo->commit();
        echo 'success';
    } catch (Exception $e) {
        $pdo->rollBack();
        echo 'Erro: ' . $e->getMessage();
    }
}