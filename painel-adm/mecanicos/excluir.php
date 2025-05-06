<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$res = $pdo->query("DELETE FROM mecanicos WHERE id = '$id'");
echo "Excluído com Sucesso!";
?>
