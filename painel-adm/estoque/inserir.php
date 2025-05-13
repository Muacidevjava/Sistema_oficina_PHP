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
            
            // Corrigindo a conversão dos valores monetários
            $valor_compra = str_replace(['R$', ' '], '', $produto['valor_compra']);
            $valor_compra = str_replace(',', '.', $valor_compra);
            
            $valor_venda = str_replace(['R$', ' '], '', $produto['valor_venda']);
            $valor_venda = str_replace(',', '.', $valor_venda);
            
            $query = $pdo->prepare("UPDATE produtos SET 
                                  estoque = estoque + :quantidade,
                                  valor_compra = :valor_compra,
                                  valor_venda = :valor_venda
                                  WHERE id = :id");
            $query->bindValue(":quantidade", $quantidade);
            $query->bindValue(":valor_compra", $valor_compra);
            $query->bindValue(":valor_venda", $valor_venda);
            $query->bindValue(":id", $id_produto);
            $query->execute();
            
            // Registrar conta a pagar
            $total = $quantidade * $valor_compra;
            $vencimento = date('Y-m-d', strtotime('+30 days'));
            
            $query = $pdo->prepare("INSERT INTO contas_pagar 
                                  (descricao, valor_total, data_vencimento, data_compra, status, id_produto, quantidade) 
                                  VALUES 
                                  (:descricao, :valor_total, :vencimento, NOW(), 'Pendente', :id_produto, :quantidade)");
            $query->bindValue(":descricao", "Compra de produto ID: ".$id_produto);
            $query->bindValue(":valor_total", $total);
            $query->bindValue(":vencimento", $vencimento);
            $query->bindValue(":id_produto", $id_produto);
            $query->bindValue(":quantidade", $quantidade);
            $query->execute();
            
            // Obter o ID da conta criada
            $id_conta = $pdo->lastInsertId();
            
            // Registrar na tabela compras
            $query = $pdo->prepare("INSERT INTO compras 
                                  (descricao, valor, funcionario, data_compra, id_produto, id_conta) 
                                  VALUES 
                                  (:descricao, :valor, :funcionario, NOW(), :id_produto, :id_conta)");
            $query->bindValue(":descricao", "Compra de produto ID: ".$id_produto); // Usando o mesmo padrão de descrição
            $query->bindValue(":valor", $total);
            $query->bindValue(":funcionario", $_SESSION['id_usuario']);
            $query->bindValue(":id_produto", $id_produto);
            $query->bindValue(":id_conta", $id_conta);
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