<?php
require_once("../../conexao.php");

try {
    $descricao = $_POST['descricao'];
    $valor_total = str_replace(['R$', ' '], '', $_POST['valor_total']);
    $valor_total = str_replace(',', '.', $valor_total);
    $data_vencimento = $_POST['data_vencimento'];
    $id_produto = !empty($_POST['id_produto']) ? $_POST['id_produto'] : null;
    $quantidade = !empty($_POST['quantidade']) ? $_POST['quantidade'] : null;
    
    if(empty($descricao) || empty($valor_total) || empty($data_vencimento)) {
        echo json_encode(['erro' => true, 'mensagem' => 'Os campos descrição, valor e data de vencimento são obrigatórios!']);
        exit();
    }

    // Inserir na tabela contas_pagar
    $query = $pdo->prepare("INSERT INTO contas_pagar 
        (descricao, valor_total, data_vencimento, data_compra, status, id_produto, quantidade) 
        VALUES (?, ?, ?, NOW(), 'Pendente', ?, ?)");
    
    $query->execute([
        $descricao, 
        $valor_total, 
        $data_vencimento,
        $id_produto,
        $quantidade
    ]);

    echo json_encode(['erro' => false, 'mensagem' => 'Conta inserida com sucesso!']);

} catch(PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => 'Erro ao inserir conta: ' . $e->getMessage()]);
}
?>