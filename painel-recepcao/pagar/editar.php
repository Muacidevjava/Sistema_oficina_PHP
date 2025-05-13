<?php
require_once("../../conexao.php");

try {
    $id = $_POST['txtid2'];
    $descricao = $_POST['descricao'];
    $valor_total = str_replace(['R$', ' '], '', $_POST['valor_total']);
    $valor_total = str_replace(',', '.', $valor_total);
    $data_vencimento = $_POST['data_vencimento'];
    $id_produto = !empty($_POST['id_produto']) ? $_POST['id_produto'] : null;
    $quantidade = !empty($_POST['quantidade']) ? $_POST['quantidade'] : 1;
    
    if(empty($descricao) || empty($valor_total) || empty($data_vencimento)) {
        echo json_encode(['erro' => true, 'mensagem' => 'Os campos descrição, valor e data de vencimento são obrigatórios!']);
        exit();
    }

    // Atualizar na tabela contas_pagar
    $query = $pdo->prepare("UPDATE contas_pagar SET 
        descricao = ?, 
        valor_total = ?, 
        data_vencimento = ?,
        id_produto = ?,
        quantidade = ?
        WHERE id = ?");
    
    $res = $query->execute([
        $descricao, 
        $valor_total, 
        $data_vencimento,
        $id_produto,
        $quantidade,
        $id
    ]);

    if($res) {
        echo json_encode(['erro' => false, 'mensagem' => 'Registro atualizado com sucesso!']);
    } else {
        echo json_encode(['erro' => true, 'mensagem' => 'Erro ao atualizar o registro!']);
    }

} catch(PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => 'Erro ao atualizar conta: ' . $e->getMessage()]);
}
?>