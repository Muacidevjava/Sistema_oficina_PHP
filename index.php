<?php
require_once ('conexao.php');

// Verificar se existe usuário admin
$query = $pdo->prepare("SELECT * FROM usuarios WHERE nivel = :nivel");
$query->bindValue(":nivel", "admin");
$query->execute();
$total_reg = $query->rowCount();

if($total_reg == 0){
    // Criar usuário admin padrão usando prepared statement
    $res = $pdo->prepare("INSERT INTO usuarios (nome, cpf, email, senha, nivel) VALUES (:nome, :cpf, :email, :senha, :nivel)");
    $res->bindValue(":nome", "Administrador");
    $res->bindValue(":cpf", "00000000000");
    $res->bindValue(":email", "admin@admin.com");
    $res->bindValue(":senha", "123456");
    $res->bindValue(":nivel", "admin");
    $res->execute();
}
?>
<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistema Oficina - Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/logo-favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/logo-favicon.ico" type="image/x-icon">

    <!-- CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css" rel="stylesheet">
    
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);
        
        html { 
            width: 100%; 
            height: 100%; 
            overflow: hidden; 
        }

        body { 
            width: 100%;
            height: 100%;
            font-family: 'Open Sans', sans-serif;
            background: #561209;
            background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), 
                        linear-gradient(to bottom, rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), 
                        linear-gradient(135deg, #670d10 0%,#561209 100%);
        }

        .login { 
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
        }

        .login h1 { 
            color: #fff; 
            text-shadow: 0 0 10px rgba(0,0,0,0.3); 
            letter-spacing: 1px; 
            text-align: center; 
        }

        .login input { 
            width: 100%; 
            margin-bottom: 10px; 
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(0,0,0,0.3);
            border-radius: 4px;
            padding: 10px;
            font-size: 13px;
            color: #fff;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
            box-shadow: inset 0 -5px 45px rgba(100,100,100,0.2), 0 1px 1px rgba(255,255,255,0.2);
            transition: box-shadow .5s ease;
        }

        .login input:focus { 
            box-shadow: inset 0 -5px 45px rgba(100,100,100,0.4), 0 1px 1px rgba(255,255,255,0.2); 
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            background-color: #4a77d4;
            border: 1px solid #3762bc;
            color: #ffffff;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.4);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .btn-login:hover {
            background-color: #385fad;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="login">
         <div align="center">
        <img src="img/logo-branca2.png" width="120" class="mb-4">
        </div>
        <form method="post" action="autenticar.php">
            <input type="email" name="email" placeholder="Email" required="required" />
            <input type="password" name="senha" placeholder="Senha" required="required" />
            <button type="submit" class="btn btn-login">Entrar</button>
            <div align="center" class="mt-2"> 
              <small><a href="" title="Clique para Recuperar sua Senha" class="text-light"> Recuperar Senha?</a></small>
           </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>