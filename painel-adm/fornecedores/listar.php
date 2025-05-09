<?php
require_once("../../conexao.php");

$nome = $_POST['nome'];

//validações
if($nome == ""){
    echo 'O nome da fornecedores é obrigatório!';
    exit();
}

//VERIFICAR SE A FORNECEDORES JÁ EXISTE NO BANCO
$query = $pdo->query("SELECT * FROM fornecedores where nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
    echo 'Fornecedores já cadastrada!';
    exit();
}

$res = $pdo->prepare("INSERT INTO fornecedores (nome) VALUES (:nome)");
$res->bindValue(":nome", $nome);
$res->execute();

echo "Salvo com Sucesso!!";
?>