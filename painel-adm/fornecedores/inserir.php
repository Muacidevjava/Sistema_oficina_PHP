<?php
require_once("../../conexao.php");
$nome = $_POST['nome_mec'];
$tipo_pessoa = $_POST['tipo_pessoa_mec'];
$telefone = $_POST['telefone_mec'];
$cpf = $_POST['cpf_mec'];
$cnpj = $_POST['cnpj_mec'];
$email = $_POST['email_mec'];
$endereco = $_POST['endereco_mec'];
$ibge = $_POST['ibge_mec'];
$descricao = $_POST['descricao_mec']; // Novo campo
$inscricao_estadual = $_POST['inscricao_estadual_mec']; // Nova informação
$inscricao_municipal = $_POST['inscricao_municipal_mec']; // Nova informação
$razao_social = $_POST['razao_social_mec']; // Nova informação
$nome_fantasia = $_POST['nome_fantasia_mec']; // Nova informação
$data_abertura = $_POST['data_abertura_mec']; // Nova informação

// Validate and format date_abertura
if(empty($data_abertura)) {
    $data_abertura = null; // Set to NULL if empty
}

$antigo = $_POST['antigo'];
$antigo2 = $_POST['antigo2'];
$id = $_POST['txtid2'];

if($tipo_pessoa == 'Física'){
    if(empty($cpf)){
        echo "O CPF é obrigatório para pessoa física!";
        exit();
    }
    $documento = $cpf;
} else {
    if(empty($cnpj)){
        echo "O CNPJ é obrigatório para pessoa jurídica!";
        exit();
    }
    $documento = $cnpj;
}

if($nome == ""){
    echo "O nome é obrigatório!";
    exit();
}
if($email == ""){
    echo "O email é obrigatório!";
    exit();
}

//verificar se o Registro já existe no banco de dados
if($antigo != $documento){
    $query = $pdo->query("SELECT * FROM fornecedores where cpf = '$documento'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if($total_reg > 0){
        echo 'Fornecedor já cadastrado!';
        exit();
    }
}
//verificar se o Registro com email já existe no banco de dados
if($id == ""){ // Se for um novo cadastro
    $query = $pdo->prepare("SELECT * FROM fornecedores WHERE email = :email");
    $query->bindValue(":email", $email);
    $query->execute();
    
    $total_reg = $query->rowCount();
    if($total_reg > 0){
        echo 'Email já cadastrado em outra conta!';
        exit();
    }
} else { // Se for uma edição
    if($antigo2 != $email){
        $query = $pdo->prepare("SELECT * FROM fornecedores WHERE email = :email AND id != :id");
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
   $res = $pdo->prepare("INSERT INTO fornecedores (nome, telefone, cpf, email, endereco, tipo_pessoa, ibge, 
                         inscricao_estadual, inscricao_municipal, razao_social, nome_fantasia, data_abertura, descricao) 
                         VALUES (:nome, :telefone, :documento, :email, :endereco, :tipo_pessoa, :ibge,
                         :inscricao_estadual, :inscricao_municipal, :razao_social, :nome_fantasia, :data_abertura, :descricao)");
   
   $res->bindValue(":nome", $nome);
   $res->bindValue(":telefone", $telefone);
   $res->bindValue(":documento", $documento);
   $res->bindValue(":email", $email);
   $res->bindValue(":endereco", $endereco);
   $res->bindValue(":tipo_pessoa", $tipo_pessoa);
   $res->bindValue(":ibge", $ibge);
   $res->bindValue(":inscricao_estadual", $inscricao_estadual);
   $res->bindValue(":inscricao_municipal", $inscricao_municipal);
   $res->bindValue(":razao_social", $razao_social);
   $res->bindValue(":nome_fantasia", $nome_fantasia);
   $res->bindValue(":data_abertura", $data_abertura);
   $res->bindValue(":descricao", $descricao);
   $res->execute();
}
else {
    //Metodo para atualizar dados no banco de dados
    $res = $pdo->prepare("UPDATE fornecedores SET 
        nome = :nome, 
        telefone = :telefone, 
        cpf = :documento, 
        email = :email, 
        endereco = :endereco, 
        tipo_pessoa = :tipo_pessoa, 
        ibge = :ibge,
        inscricao_estadual = :inscricao_estadual,
        inscricao_municipal = :inscricao_municipal,
        razao_social = :razao_social,
        nome_fantasia = :nome_fantasia,
        data_abertura = :data_abertura,
        descricao = :descricao
        WHERE id = :id");
        
    $res->bindValue(":id", $id);
    $res->bindValue(":nome", $nome);
    $res->bindValue(":telefone", $telefone);
    $res->bindValue(":documento", $documento);
    $res->bindValue(":email", $email);
    $res->bindValue(":endereco", $endereco);
    $res->bindValue(":tipo_pessoa", $tipo_pessoa);
    $res->bindValue(":ibge", $ibge);
    $res->bindValue(":inscricao_estadual", $inscricao_estadual);
    $res->bindValue(":inscricao_municipal", $inscricao_municipal);
    $res->bindValue(":razao_social", $razao_social);
    $res->bindValue(":nome_fantasia", $nome_fantasia);
    $res->bindValue(":data_abertura", $data_abertura);
    $res->bindValue(":descricao", $descricao);
    $res->execute();
}

echo "Salvo com Sucesso!!";
?>



