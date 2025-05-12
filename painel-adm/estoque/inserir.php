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
            
            // Obter valores atuais do produto
            $query = $pdo->prepare("SELECT valor_compra, valor_venda FROM produtos WHERE id = :id");
            $query->bindValue(":id", $id_produto);
            $query->execute();
            $produto_info = $query->fetch(PDO::FETCH_ASSOC);
            
            // Atualizar estoque e valores
            $query = $pdo->prepare("UPDATE produtos SET 
                                  estoque = estoque + :quantidade,
                                  valor_compra = :valor_compra,
                                  valor_venda = :valor_venda
                                  WHERE id = :id");
            $query->bindValue(":quantidade", $quantidade);
            $query->bindValue(":valor_compra", $produto_info['valor_compra']);
            $query->bindValue(":valor_venda", $produto_info['valor_venda']);
            $query->bindValue(":id", $id_produto);
            $query->execute();
        }
        
        $pdo->commit();
        echo 'success';
    } catch (Exception $e) {
        $pdo->rollBack();
        echo 'Erro: ' . $e->getMessage();
    }
}
?>