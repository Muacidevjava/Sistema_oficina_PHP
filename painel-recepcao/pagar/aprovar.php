<?php
require_once("../../conexao.php");

$id = $_POST['id'];

try {
    // Atualiza o status para Pago e registra a data de pagamento
    $res = $pdo->prepare("UPDATE contas_pagar SET status = 'Pago', data_pagamento = NOW() WHERE id = ?");
    $res->execute([$id]);
    
    echo json_encode(['erro' => false, 'mensagem' => 'Conta aprovada com sucesso!']);

} catch(PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => 'Erro ao aprovar conta: ' . $e->getMessage()]);
}
?>