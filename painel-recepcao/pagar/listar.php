<?php
require_once("../../conexao.php");

$query = $pdo->query("SELECT * FROM categorias order by nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0; $i < count($res); $i++) {
    $nome_cat = $res[$i]['nome'];
    $id_cat = $res[$i]['id'];
    echo '<option value="'.$id_cat.'">'.$nome_cat.'</option>';
}
?>