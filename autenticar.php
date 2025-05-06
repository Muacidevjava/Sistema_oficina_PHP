<?php
require_once("conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

// Usando prepared statement para maior segurança
$query = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
$query->bindValue(":email", $email);
$query->bindValue(":senha", $senha);
$query->execute();

$total_reg = $query->rowCount();
if($total_reg > 0){
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    session_start();
    $_SESSION['id_usuario'] = $res[0]['id'];
    $_SESSION['nome_usuario'] = $res[0]['nome'];
    $_SESSION['nivel_usuario'] = $res[0]['nivel'];
    
    echo 'Bem vindo!';
} else {
    echo 'Dados Incorretos!';
}
?>