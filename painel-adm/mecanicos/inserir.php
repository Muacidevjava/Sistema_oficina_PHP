<?php
require_once("../../conexao.php");
$nome = $_POST['nome_mec'];
$telefone = $_POST['telefone_mec'];
$cpf = $_POST['cpf_mec'];
$email = $_POST['email_mec'];
$endereco = $_POST['endereco_mec'];

$query = $pdo->query("SELECT * FROM mecanicos where cpf ='$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tota_reg = @count($res);
echo $tota_reg;
?>