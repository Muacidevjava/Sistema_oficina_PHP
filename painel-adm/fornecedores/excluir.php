<?php
require_once("../../conexao.php");

$id = $_POST['id'];

// Buscar o CPF do mecânico antes de excluir
$query = $pdo->prepare("SELECT cpf FROM mecanicos WHERE id = :id");
$query->bindValue(":id", $id);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$cpf = $res[0]['cpf'];

// Excluir da tabela mecanicos
$res = $pdo->prepare("DELETE FROM mecanicos WHERE id = :id");
$res->bindValue(":id", $id);
$res->execute();

// Excluir também da tabela usuarios
$res = $pdo->prepare("DELETE FROM usuarios WHERE cpf = :cpf");
$res->bindValue(":cpf", $cpf);
$res->execute();

echo "Excluído com Sucesso!";
?>
