<?php
require_once('../conexao.php');
session_start();

$nome = $_POST['nome_usu'];
$cpf = $_POST['cpf_usu'];
$email = $_POST['email_usu'];
$senha = $_POST['senha_usu'];
$antigo = $_POST['antigo_usu'];
$id = $_POST['id_usu'];

if($nome == ""){
    echo "O nome é obrigatório!";
    exit();
}
if($email == ""){
    echo "O email é obrigatório!";
    exit();
}
if($cpf == ""){
    echo "O cpf é obrigatório!";
    exit();
}

//verificar se o CPF já existe no banco de dados
if($antigo != $cpf){
    $query = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = :cpf AND id != :id");
    $query->bindValue(":cpf", $cpf);
    $query->bindValue(":id", $id);
    $query->execute();
    
    if($query->rowCount() > 0){
        echo 'CPF já cadastrado em outra conta!';
        exit();
    }
}

try {
    if($senha == ""){
        $res = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email WHERE id = :id");
    } else {
        $res = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email, senha = :senha WHERE id = :id");
        $res->bindValue(":senha", $senha);
    }

    $res->bindValue(":nome", $nome);
    $res->bindValue(":cpf", $cpf);
    $res->bindValue(":email", $email);
    $res->bindValue(":id", $id);
    
    $res->execute();
    
    $_SESSION['nome_usuario'] = $nome;
    
    echo "Salvo com Sucesso!!";
} catch(PDOException $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
// Remover esta linha que estava causando o problema
// echo "<script>window.location='painel-adm'</script>";
?>





