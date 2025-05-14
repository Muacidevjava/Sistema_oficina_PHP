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
    <a type="button" onclick="abrirModalContaAvulsa()" style="color:white" class="btn btn-danger btn-sm ml-3 d-none d-md-block">Adicionar Contas Avulsas</a>
    <a type="button" onclick="abrirModalContaAvulsa()" class="btn btn-primary btn-sm ml-3 d-block d-sm-none">+</a>
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
                        <th>Data Pagamento</th>
                        <th>Funcionario</th>
                        <th>status</th>
                        
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT * FROM contas_pagar  order by id desc ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $descricao = $res[$i]['descricao'];
                        $valor_total = $res[$i]['valor_total'];
                        $data_vencimento = $res[$i]['data_vencimento'];
                        $data_compra = $res[$i]['data_compra'];
                        $data_pagamento = $res[$i]['data_pagamento'];
                        $status = $res[$i]['status'];
                       
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

                        $query_func = $pdo->prepare("SELECT u.nome as nome_funcionario
                                                   FROM compras c 
                                                   INNER JOIN contas_pagar cp ON c.id_conta = cp.id 
                                                   INNER JOIN usuarios u ON c.funcionario = u.id
                                                   WHERE cp.id = ? ");
                        $query_func->execute([$id]);
                        $res_func = $query_func->fetchAll(PDO::FETCH_ASSOC);
                        $nome_funcionario = $res_func[0]['nome_funcionario'] ?? 'Funcionário não encontrado';

                         
                    ?>


                        <tr>
                            <td><i class="fas fa-square mr-1 <?php echo $cor_pago ?>"></i>
                                <?php echo $descricao ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_total, 2, ',', '.') ?></td>
                            <td><?php echo $data_vencimento ?></td>
                            <td><?php echo $data_compra ?></td>
                            <td><?php echo $data_pagamento ?? '-' ?></td>
                            <td><?php echo $nome_funcionario ?></td>
                            <td class="<?php echo $class ?>"><?php echo $status ?></td>
                            <td>
                                <?php
                                if (strtolower($status) != 'pago') {?>
                                    
                               
                                <!-- <a href="#" onclick="abrirModalContaAvulsa()" class='text-primary mr-1' title='Adicionar Contas avulsas'><i class='fas fa-plus-square'></i></a> -->
                                <a href="#" onclick="editar(<?php echo $id ?>)" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
                                <a href="#" onclick="excluir(<?php echo $id ?>)" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
                                <a href="#" onclick="aprovar(<?php echo $id ?>)" class='text-success mr-1' title='Aprovar Conta'><i class='fas fa-check-square'></i></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php } ?>





                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Descrição</label>
                        <input type="text" class="form-control" id="descricao-editar" name="descricao" required>
                    </div>

                    <div class="form-group">
                        <label>Valor Total</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control money" id="valor_total-editar" name="valor_total" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Data Vencimento</label>
                        <input type="date" class="form-control" id="data_vencimento-editar" name="data_vencimento" required>
                    </div>

                    <div class="form-group">
                        <label>ID do Produto (opcional)</label>
                        <input type="number" class="form-control" id="id_produto-editar" name="id_produto">
                    </div>

                    <div class="form-group">
                        <label>Quantidade (opcional)</label>
                        <input type="number" class="form-control" id="quantidade-editar" name="quantidade" min="0">
                    </div>

                    <input type="hidden" id="txtid2" name="txtid2">
                    <small><div id="mensagem-editar"></div></small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Excluir -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Excluir Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3em;"></i>
                </div>
                <p class="text-center">Tem certeza que deseja excluir Esse pagamento?</p>
                <p class="text-center text-danger"><small>Esta ação não poderá ser desfeita!</small></p>
                <input type="hidden" id="id-exc" name="id" value="">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div id="mensagem-excluir" class="text-danger"></div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-excluir" class="btn btn-danger">Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function excluir(id){
        $('#id-exc').val(id);
        $('#mensagem-excluir').text('');
        $('#modalExcluir').modal('show');
    }

    $(document).ready(function(){
        $('#btn-excluir').click(function(){
            var id = $('#id-exc').val();
            $.ajax({
                url: 'pagar/excluir.php',
                method: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response){
                    if(response.erro){
                        $('#mensagem-excluir').addClass('text-danger').removeClass('text-success').text(response.mensagem);
                    } else {
                        $('#mensagem-excluir').addClass('text-success').removeClass('text-danger').text(response.mensagem);
                        setTimeout(function(){
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(){
                    $('#mensagem-excluir').addClass('text-danger').removeClass('text-success').text('Erro ao processar a solicitação');
                }
            });
        });
    });
</script>

<!-- Modal Aprovar -->
<div class="modal fade" id="modalAprovar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Aprovar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-question-circle text-warning" style="font-size: 3em;"></i>
                </div>
                <p class="text-center">Deseja realmente aprovar este Pagamento?</p>
                <input type="hidden" id="id-apr" name="id" value="">
                
                <div id="mensagem-aprovar" class="text-center mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-aprovar" class="btn btn-success">Aprovar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function aprovar(id){
    $('#id-apr').val(id);
    $('#mensagem-aprovar').text('');
    $('#modalAprovar').modal('show');
}

$(document).ready(function(){
    $('#btn-aprovar').click(function(){
        var id = $('#id-apr').val();
        $.ajax({
            url: 'pagar/aprovar.php',
            method: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(response){
                if(response.erro){
                    $('#mensagem-aprovar').removeClass('text-success').addClass('text-danger').text(response.mensagem);
                } else {
                    $('#mensagem-aprovar').removeClass('text-danger').addClass('text-success').text(response.mensagem);
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(){
                $('#mensagem-aprovar').removeClass('text-success').addClass('text-danger').text('Erro ao processar a solicitação');
            }
        });
    });
});
</script>

<!-- Modal Adicionar Conta Avulsa -->
<div class="modal fade" id="modalContaAvulsa" tabindex="-1" role="dialog" aria-labelledby="modalContaAvulsaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalContaAvulsaLabel">Inserir Conta Avulsa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-conta-avulsa" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>

                    <div class="form-group">
                        <label>Valor Total</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control money" id="valor_total" name="valor_total" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Data Vencimento</label>
                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
                    </div>

                    <div class="form-group">
                        <label>ID do Produto (opcional)</label>
                        <input type="number" class="form-control" id="id_produto" name="id_produto">
                    </div>

                    <div class="form-group">
                        <label>Quantidade (opcional)</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" min="0" placeholder="Adicione a Quantidade">
                    </div>

                    <small>
                        <div id="mensagem-conta-avulsa"></div>
                    </small>
                </div>
             <div class="col-md-6">
                <div class="row">
                       
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Imagem</label>
                                <input type="file" value="<?php echo @$imagem2 ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg()" accept="image/jpeg,image/jpg,image/png,image/gif">
                            </div>

                            <div id="divImg">
                                <?php if(@$imagem2 != "") { ?>
                                    <img src="../img/contas/<?php echo $imagem2 ?>" width="100" height="100" id="target">
                                <?php } else { ?>
                                    <img src="../img/contas/sem-foto.jpg" width="100" height="100" id="target">
                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Máscara para campo de valor
        $('.money').mask('#.##0,00', {reverse: true});
        
        // Data mínima como hoje
        var today = new Date().toISOString().split('T')[0];
        $('#data_vencimento').attr('min', today);
    });

    function abrirModalContaAvulsa() {
        $('#modalContaAvulsa').modal('show');
        $('#mensagem-conta-avulsa').text('');
        $('#form-conta-avulsa')[0].reset();
        
        // Define a data de hoje como padrão
        var today = new Date().toISOString().split('T')[0];
        $('#data_vencimento').val(today);
    }

    $('#form-conta-avulsa').submit(function(e) {
        e.preventDefault();
        var dados = $(this).serialize();
        
        $.ajax({
            url: 'pagar/inserir-avulso.php',
            method: 'POST',
            data: dados,
            dataType: 'json',
            success: function(response) {
                if(response.erro) {
                    $('#mensagem-conta-avulsa').addClass('text-danger').removeClass('text-success').text(response.mensagem);
                } else {
                    $('#mensagem-conta-avulsa').addClass('text-success').removeClass('text-danger').text(response.mensagem);
                    $('#modalContaAvulsa').modal('hide');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function() {
                $('#mensagem-conta-avulsa').addClass('text-danger').removeClass('text-success').text('Erro ao processar solicitação');
            }
        });
    });
</script>

<script type="text/javascript">
function editar(id) {
    $.ajax({
        url: 'pagar/buscar.php',
        method: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.erro) {
                // Se houver erro ao buscar os dados
                $('#mensagem-editar').addClass('text-danger').removeClass('text-success').html(data.mensagem);
                return;
            }
            
            $('#txtid2').val(data.id);
            $('#descricao-editar').val(data.descricao);
            $('#valor_total-editar').val(data.valor_total);
            $('#data_vencimento-editar').val(data.data_vencimento);
            $('#id_produto-editar').val(data.id_produto);
            $('#quantidade-editar').val(data.quantidade);
            
            $('#modalDados').modal('show');
            $('#mensagem-editar').text('').removeClass('text-danger text-success');
        },
        error: function() {
            alert('Erro ao buscar dados do registro!');
        }
    });
}

$('#form').submit(function(e) {
    e.preventDefault();
    
    // Validação dos campos obrigatórios
    if(!$('#descricao-editar').val() || !$('#valor_total-editar').val() || !$('#data_vencimento-editar').val()) {
        $('#mensagem-editar').addClass('text-danger').removeClass('text-success')
            .html('Por favor, preencha todos os campos obrigatórios!');
        return false;
    }
    
    var dados = $(this).serialize();
    
    $.ajax({
        url: 'pagar/editar.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        success: function(response) {
            if(response.erro) {
                $('#mensagem-editar').addClass('text-danger').removeClass('text-success')
                    .html('<i class="fas fa-times-circle"></i> ' + response.mensagem);
            } else {
                $('#mensagem-editar').addClass('text-success').removeClass('text-danger')
                    .html('<i class="fas fa-check-circle"></i> ' + response.mensagem);
                
                // Aguarda 2 segundos antes de fechar a modal e recarregar
                setTimeout(function() {
                    $('#modalDados').modal('hide');
                    window.location.reload();
                }, 2000);
            }
        },
        error: function() {
            $('#mensagem-editar').addClass('text-danger').removeClass('text-success')
                .html('<i class="fas fa-times-circle"></i> Erro ao processar solicitação. Tente novamente!');
        }
    });
});

// Máscara para campo de valor no modal de edição
$(document).ready(function() {
    $('.money').mask('#.##0,00', {reverse: true});
});
</script>