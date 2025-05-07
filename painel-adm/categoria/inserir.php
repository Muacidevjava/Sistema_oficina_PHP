<?php
require_once("../../conexao.php");

// Recebe e sanitiza os dados
$nome = trim(strip_tags($_POST['nome_mec']));
$antigo = trim($_POST['antigo']);
$id = $_POST['txtid2'];

// Validação básica
if(empty($nome)){
    echo "O nome da categoria é obrigatório!";
    exit();
}

try {
    // Verificar se a categoria já existe
    if($antigo != $nome){
        $query = $pdo->prepare("SELECT * FROM categorias WHERE nome = :nome");
        $query->bindValue(":nome", $nome);
        $query->execute();
        
        if($query->rowCount() > 0){
            echo 'Categoria já cadastrada!';
            exit();
        }
    }

    // Preparar a query (inserir ou atualizar)
    if(empty($id)) {
        // Inserir nova categoria
        $query = $pdo->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
        $query->bindValue(":nome", $nome);
        $query->execute();
    } else {
        // Atualizar categoria existente
        $query = $pdo->prepare("UPDATE categorias SET nome = :nome WHERE id = :id");
        $query->bindValue(":nome", $nome);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    echo "Salvo com Sucesso!";

} catch(PDOException $e) {
    echo "Erro ao salvar categoria: " . $e->getMessage();
}
?>



