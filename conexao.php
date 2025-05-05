<?php
require_once("config.php");

date_default_timezone_set('America/Sao_Paulo');

try{
    $pdo = new  PDO ("mysql:dbname=".$banco.";host=".$servidor,$usuario,$senha);

    //conexao mysqli para o backup
    $conn = mysqli_connect($servidor,$usuario,$senha,$banco);
}catch(PDOException $e){
    echo "erro com banco de dados: ".$e->getMessage();
    exit();}


?>