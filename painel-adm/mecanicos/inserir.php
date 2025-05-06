<?php
require_once("../../conexao.php");
$nome = $_POST['nome_mec'];
$telefone = $_POST['telefone_mec'];
$cpf = $_POST['cpf_mec'];
$email = $_POST['email_mec'];
$endereco = $_POST['endereco_mec'];

$antigo = $_POST['antigo'];
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

if ($id == "") {
   //Metodo para inserir dados no banco de dados
   $res = $pdo->prepare("INSERT INTO mecanicos (nome, telefone, cpf, email, endereco) VALUES (:nome, :telefone, :cpf, :email, :endereco)");
}
else {
    //Metodo para atualizar dados no banco de dados
    $res = $pdo->prepare("UPDATE mecanicos SET nome = :nome, telefone = :telefone, cpf = :cpf, email = :email, endereco = :endereco WHERE id = :id");
    $res->bindValue(":id", $id);
}

$res->bindValue(":nome", $nome);
$res->bindValue(":telefone", $telefone);
$res->bindValue(":cpf", $cpf);
$res->bindValue(":email", $email);
$res->bindValue(":endereco", $endereco);
$res->execute();
 echo "Salvo com Sucesso!!"; 

?>



