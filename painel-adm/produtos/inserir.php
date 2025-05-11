<?php
require_once("../../conexao.php");

$nome = $_POST['nome'];
$categoria = $_POST['categoria'];
$fornecedor = $_POST['fornecedor'];

// Corrige formatação dos valores monetários para o padrão brasileiro
$valor_compra = $_POST['valor_compra'];
$valor_venda = $_POST['valor_venda'];

// Remove o R$ e espaços se houver
$valor_compra = str_replace('R$ ', '', $valor_compra);
$valor_venda = str_replace('R$ ', '', $valor_venda);

// Remove todos os pontos e substitui vírgula por ponto
$valor_compra = str_replace('.', '', $valor_compra);
$valor_compra = str_replace(',', '.', $valor_compra);

$valor_venda = str_replace('.', '', $valor_venda);
$valor_venda = str_replace(',', '.', $valor_venda);

$estoque = $_POST['estoque'];
$descricao = $_POST['descricao'];

$antigo = $_POST['antigo'];
$id = $_POST['txtid2'];

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = preg_replace('/[ -]+/' , '-' , @$_FILES['imagem']['name']);
$caminho = '../../img/produtos/' . $nome_img;

if (@$_FILES['imagem']['name'] == ""){
  $imagem = "sem-foto.jpg";
}else{
  // Validação do tipo de arquivo
  $permitidos = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
  if (!in_array($_FILES['imagem']['type'], $permitidos)) {
    echo 'Tipo de arquivo não permitido! Selecione apenas imagens (JPG, PNG ou GIF)';
    exit();
  }
  
  // Validação do tamanho do arquivo (máximo 2MB)
  if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
    echo 'Arquivo muito grande! Tamanho máximo permitido: 2MB';
    exit();
  }
  
  $imagem = $nome_img;
}

//validações
if($nome == ""){
    echo 'O nome é obrigatório!';
    exit();
}

if($valor_venda == ""){
    echo 'O valor de venda é obrigatório!';
    exit();
}

if($valor_compra == ""){
    echo 'O valor de compra é obrigatório!';
    exit();
}

if($estoque == ""){
    echo 'O estoque é obrigatório!';
    exit();
}

// Garante que o estoque seja um número inteiro
$estoque = intval($estoque);

//VERIFICAR SE O REGISTRO JÁ EXISTE NO BANCO
if($antigo != $nome){
    $query = $pdo->query("SELECT * FROM produtos where nome = '$nome'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if($total_reg > 0){
        echo 'Produto já cadastrado!';
        exit();
    }
}

try {
    if($id == ""){
        $res = $pdo->prepare("INSERT INTO produtos (nome, categoria, fornecedor, valor_compra, valor_venda, estoque, descricao, imagem, data_cadastro) VALUES (:nome, :categoria, :fornecedor, :valor_compra, :valor_venda, :estoque, :descricao, :imagem, NOW())");
        $res->bindValue(":imagem", $imagem);
    } else {
        if($imagem == "sem-foto.jpg" && @$_FILES['imagem']['name'] == ""){
            $res = $pdo->prepare("UPDATE produtos SET nome = :nome, categoria = :categoria, fornecedor = :fornecedor, valor_compra = :valor_compra, valor_venda = :valor_venda, estoque = :estoque, descricao = :descricao WHERE id = :id");
        }else{
            $res = $pdo->prepare("UPDATE produtos SET nome = :nome, categoria = :categoria, fornecedor = :fornecedor, valor_compra = :valor_compra, valor_venda = :valor_venda, estoque = :estoque, descricao = :descricao, imagem = :imagem WHERE id = :id");
            $res->bindValue(":imagem", $imagem);
        }
        $res->bindValue(":id", $id);
    }

    $res->bindValue(":nome", $nome);
    $res->bindValue(":categoria", $categoria);
    $res->bindValue(":fornecedor", $fornecedor);
    $res->bindValue(":valor_compra", $valor_compra);
    $res->bindValue(":valor_venda", $valor_venda);
    $res->bindValue(":estoque", $estoque);
    $res->bindValue(":descricao", $descricao);

    $res->execute();

    if(@$_FILES['imagem']['name'] != ""){
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
    }

    echo "Salvo com Sucesso!!";

} catch(PDOException $e) {
    echo 'Erro ao salvar: ' . $e->getMessage();
    exit();
}
?>



