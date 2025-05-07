<?php
require_once('conexao.php');

$email = $_POST['email'];

// Validação básica do email
if($email == ""){
    echo "Digite seu Email";
    exit();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "Email inválido!";
    exit();
}

try {
    // Buscar usuário pelo email usando prepared statement
    $res = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $res->bindValue(":email", $email);
    $res->execute();
    
    if($res->rowCount() > 0){
        $dados = $res->fetch(PDO::FETCH_ASSOC);
        
        // Gerar nova senha aleatória
        $nova_senha = substr(md5(time()), 0, 6);
        
        // Atualizar senha no banco
        $res_update = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE email = :email");
        $res_update->bindValue(":senha", $nova_senha);
        $res_update->bindValue(":email", $email);
        $res_update->execute();
        
        // Como é uma versão básica, apenas exibimos a senha
        echo "Sua nova senha é: " . $nova_senha;
        
    } else {
        echo "Email não cadastrado!";
    }
} catch(PDOException $e) {
    echo "Erro ao recuperar senha: " . $e->getMessage();
}
?>

//criar email funcção para enviara email com senha nova
//enviar email com senha nova
