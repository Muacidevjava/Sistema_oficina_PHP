<?php
require_once("../../conexao.php");

$nome = $_POST['nome_rec'];
$cpf = $_POST['cpf_rec'];
$email = $_POST['email_rec'];
$telefone = $_POST['telefone_rec'];
$endereco = $_POST['endereco_rec'];

$antigo = $_POST['antigo'];
$antigo2 = $_POST['antigo2'];
$id = $_POST['txtid2'];

// Validações básicas
if($nome == ""){
    echo "O nome é obrigatório!";
    exit();
}
if($email == ""){
    echo "O email é obrigatório!";
    exit();
}
if($cpf == ""){
    echo "O CPF é obrigatório!";
    exit();
}

// Verificar CPF duplicado
if($antigo != $cpf){
    $query = $pdo->prepare("SELECT * FROM recepcionistas WHERE cpf = :cpf");
    $query->bindValue(":cpf", $cpf);
    $query->execute();
    
    if($query->rowCount() > 0){
        echo 'CPF já cadastrado!';
        exit();
    }
}

// Verificar email duplicado
if($antigo2 != $email){
    $query = $pdo->prepare("SELECT * FROM recepcionistas WHERE email = :email AND id != :id");
    $query->bindValue(":email", $email);
    $query->bindValue(":id", $id);
    $query->execute();
    
    if($query->rowCount() > 0){
        echo 'Email já cadastrado em outra conta!';
        exit();
    }
}

if($id == ""){
    // Inserir novo recepcionista
    $res = $pdo->prepare("INSERT INTO recepcionistas (nome, cpf, email, telefone, endereco) VALUES (:nome, :cpf, :email, :telefone, :endereco)");
    
    // Inserir também na tabela de usuários
    $res2 = $pdo->prepare("INSERT INTO usuarios (nome, nivel, cpf, email, senha) VALUES (:nome, :nivel, :cpf, :email, :senha)");
    
    $res->bindValue(":nome", $nome);
    $res->bindValue(":cpf", $cpf);
    $res->bindValue(":email", $email);
    $res->bindValue(":telefone", $telefone);
    $res->bindValue(":endereco", $endereco);
    $res->execute();

    $res2->bindValue(":nome", $nome);
    $res2->bindValue(":nivel", 'recep');
    $res2->bindValue(":cpf", $cpf);
    $res2->bindValue(":email", $email);
    $res2->bindValue(":senha", '123');
    $res2->execute();
} else {
    // Atualizar recepcionista existente
    $res = $pdo->prepare("UPDATE recepcionistas SET nome = :nome, cpf = :cpf, email = :email, telefone = :telefone, endereco = :endereco WHERE id = :id");
    
    $res->bindValue(":nome", $nome);
    $res->bindValue(":cpf", $cpf);
    $res->bindValue(":email", $email);
    $res->bindValue(":telefone", $telefone);
    $res->bindValue(":endereco", $endereco);
    $res->bindValue(":id", $id);
    $res->execute();

    // Atualizar também na tabela usuarios
    $res2 = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email WHERE cpf = :cpf_antigo");
    $res2->bindValue(":nome", $nome);
    $res2->bindValue(":cpf", $cpf);
    $res2->bindValue(":email", $email);
    $res2->bindValue(":cpf_antigo", $antigo);
    $res2->execute();
}

echo "Salvo com Sucesso!";
?>