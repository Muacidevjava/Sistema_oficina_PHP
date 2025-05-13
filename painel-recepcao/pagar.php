<?php
session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'recep') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "pagar";
require_once("../conexao.php");


?>

<div class="row mt-4 mb-4">
    <a type="button" class="btn-danger btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Nova Conta</a>
    <a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none" href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Descricao</th>
                        <th>Valor Total</th>
                        <th>Data Vencimento</th>
                        <th>Data da Compra</th>
                        <th>Funcionario</th>
                        <th>status</th>
                        
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT * FROM contas_pagar order by id desc ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $descricao = $res[$i]['descricao'];
                        $valor_total = $res[$i]['valor_total'];
                        $data_vencimento = $res[$i]['data_vencimento'];
                        $data_compra = $res[$i]['data_compra'];
                        $status = $res[$i]['status'];
                        $usuario = $res[$i]['usuario']; // Alterado de funcionario para usuario
                        $query_usu = $pdo->prepare("SELECT * FROM usuarios where id = ?");
                        $query_usu->execute([$usuario]);
                        $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                        $nome_usuario = $res_usu[0]['nome'];

                        if (strtolower($status) == 'pago') {
                            $class = 'text-success';
                            $var = '';
                            $cor_pago = 'text-success';
                        } else {
                            $class = '';
                            $var = '';
                            $cor_pago = 'text-danger';
                        }

                        $id = $res[$i]['id'];

                        $query_tot = $pdo->query("SELECT * FROM produtos where categoria = $id");
                        $res_tot = $query_tot->fetchAll(PDO::FETCH_ASSOC);
                        $total_produtos = @count($res_tot);

                         
                    ?>


                        <tr>
                            <td><i class="fas fa-square mr-1 <?php echo $cor_pago ?>"></i>
                                <?php echo $descricao ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_total, 2, ',', '.') ?></td>
                            <td><?php echo $data_vencimento ?></td>
                            <td><?php echo $data_compra ?></td>
                            <td><?php echo $nome_usuario ?></td>
                            <td class="<?php echo $class ?>"><?php echo $status ?></td>
                            <td>
                                <?php
                                if (strtolower($status) != 'pago') {?>
                                    
                               
                                <a href="index.php?pag=produtos&funcao=novo&id=<?php echo $id?>" class='text-primary mr-1' title='Adicionar Produtos'><i class='fas fa-plus-square'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>" class='text-success mr-1' title='Aprovar Conta'><i class='fas fa-check-square'></i></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php } ?>





                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Registro";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM categorias where id = '" . $id2 . "' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome2 = $res[0]['nome'];
                } else {
                    $titulo = "Inserir Registro";
                }
                ?>

                <h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome_mec" name="nome_mec" placeholder="Nome">
                    </div>

                    <small>
                        <div id="mensagem">
                        </div>
                    </small>
                </div>

                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$cpf2 ?>" type="hidden" name="antigo" id="antigo">
                    <input value="<?php echo @$email2 ?>" type="hidden" name="antigo2" id="antigo2">

                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>