<?php
require_once("../../conexao.php");

$id = $_POST['id'];

$query = $pdo->prepare("SELECT * FROM contas_pagar WHERE id = ?");
$query->execute([$id]);
$resultado = $query->fetch(PDO::FETCH_ASSOC);

if($resultado) {
    $resultado['valor_total'] = number_format($resultado['valor_total'], 2, ',', '.');
    echo json_encode($resultado);
} else {
    echo json_encode(['erro' => true, 'mensagem' => 'Registro não encontrado']);
}
?>