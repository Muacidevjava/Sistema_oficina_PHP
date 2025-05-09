<?php
require_once("../../conexao.php");

$id = $_POST['id'];

//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
$query = $pdo->query("SELECT * FROM produtos where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$imagem = $res[0]['imagem'];

if($imagem != 'sem-foto.jpg'){
    unlink('../../img/produtos/'.$imagem);
}

$query = $pdo->query("DELETE FROM produtos where id = '$id'");

echo 'Excluído com Sucesso!';
?>

