<?php
require_once("../../conexao.php");
$nome = $_POST['nome_mec'];
$telefone = $_POST['telefone_mec'];
$cpf = $_POST['cpf_mec'];
$email = $_POST['email_mec'];
$endereco = $_POST['endereco_mec'];

$antigo = $_POST['antigo'];
$antigo2 = $_POST['antigo2'];
$id = $_POST['txtid2'];

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

//verificar se o Registro já existe no banco de dados
if($antigo != $cpf){
	$query = $pdo->query("SELECT * FROM mecanicos where cpf ='$cpf'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tota_reg = @count($res);
if($tota_reg > 0){
	echo 'CPF já cadastrado!';
	exit();
}
}
//verificar se o Registro com email já existe no banco de dados
if($id == ""){ // Se for um novo cadastro
    $query = $pdo->prepare("SELECT * FROM mecanicos WHERE email = :email");
    $query->bindValue(":email", $email);
    $query->execute();
    
    $total_reg = $query->rowCount();
    if($total_reg > 0){
        echo 'Email já cadastrado em outra conta!';
        exit();
    }
} else { // Se for uma edição
    if($antigo2 != $email){
        $query = $pdo->prepare("SELECT * FROM mecanicos WHERE email = :email AND id != :id");
        $query->bindValue(":email", $email);
        $query->bindValue(":id", $id);
        $query->execute();
        
        $total_reg = $query->rowCount();
        if($total_reg > 0){
            echo 'Email já cadastrado em outra conta!';
            exit();
        }
    }
}


if ($id == "") {
   //Metodo para inserir dados no banco de dados
   $res = $pdo->prepare("INSERT INTO mecanicos (nome, telefone, cpf, email, endereco) VALUES (:nome, :telefone, :cpf, :email, :endereco) ");
   
   $res2 = $pdo->prepare("INSERT INTO usuarios (nome, nivel, cpf, email, senha) VALUES (:nome, :nivel, :cpf, :email, :senha)");
   
   $res->bindValue(":nome", $nome);
   $res->bindValue(":telefone", $telefone);
   $res->bindValue(":cpf", $cpf);
   $res->bindValue(":email", $email);
   $res->bindValue(":endereco", $endereco);
   $res->execute();

   $res2->bindValue(":nome", $nome);
   $res2->bindValue(":nivel", 'mecanico');
   $res2->bindValue(":cpf", $cpf);
   $res2->bindValue(":email", $email);
   $res2->bindValue(":senha", '123');
   $res2->execute();
}
else {
    //Metodo para atualizar dados no banco de dados
    $res = $pdo->prepare("UPDATE mecanicos SET nome = :nome, telefone = :telefone, cpf = :cpf, email = :email, endereco = :endereco WHERE id = :id");
    $res->bindValue(":id", $id);
    $res->bindValue(":nome", $nome);
    $res->bindValue(":telefone", $telefone);
    $res->bindValue(":cpf", $cpf);
    $res->bindValue(":email", $email);
    $res->bindValue(":endereco", $endereco);
    $res->execute();

    // Atualizar também na tabela usuarios
    $res2 = $pdo->prepare("UPDATE usuarios SET nome = :nome, cpf = :cpf, email = :email WHERE cpf = :cpf_antigo");
    $res2->bindValue(":nome", $nome);
    $res2->bindValue(":cpf", $cpf);
    $res2->bindValue(":email", $email);
    $res2->bindValue(":cpf_antigo", $antigo);
    $res2->execute();
}

echo "Salvo com Sucesso!!"; 
?>



