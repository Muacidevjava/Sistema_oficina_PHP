<?php
require_once("../../conexao.php");

$id = $_POST['id'];

try {
    // Primeiro verificar se a conta não está paga
    $query = $pdo->prepare("SELECT status FROM contas_pagar WHERE id = ?");
    $query->execute([$id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if (strtolower($result['status']) == 'pago') {
        echo json_encode(['erro' => true, 'mensagem' => 'Não é possível excluir uma conta já paga!']);
        exit();
    }

    // Excluir registros relacionados na tabela compras
    $query = $pdo->prepare("DELETE FROM compras WHERE id_conta = ?");
    $query->execute([$id]);

    // Excluir a conta
    $query = $pdo->prepare("DELETE FROM contas_pagar WHERE id = ?");
    $query->execute([$id]);

    echo json_encode(['erro' => false, 'mensagem' => 'Excluído com sucesso!']);

} catch(PDOException $e) {
    echo json_encode(['erro' => true, 'mensagem' => 'Erro ao excluir: ' . $e->getMessage()]);
}
?>
