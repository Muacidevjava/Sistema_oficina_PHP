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
    
    // Redirecionamento baseado no nível do usuário
    if($_SESSION['nivel_usuario'] == 'admin'){
        echo "<script>window.location='painel-adm'</script>";
    }
    else if($_SESSION['nivel_usuario'] == 'mecanico'){
        echo "<script>window.location='painel-mecanico'</script>";
    }
    else if($_SESSION['nivel_usuario'] == 'recepcionista'){
        echo "<script>window.location='painel-recep'</script>";
    }
} else {
    echo "<script>window.alert('Dados Incorretos!')</script>";
    echo "<script>window.location='index.php'</script>";
}
?>