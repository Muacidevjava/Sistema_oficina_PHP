<?php
session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'admin') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "produtos";  // Nome correto da página
require_once("../conexao.php");



?>

<div class="row mt-4 mb-4">
    <a type="button" class="btn-danger btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Novo Produto</a>
    <a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none" href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Fornecedor</th>
                        <th>Valor de Compra</th>
                        <th>Valor de venda</th>
                        <th>Estoque</th>
                        <th>Descrição</th>
                        <th>Imagem</th>
                        <th>Data cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT p.*, f.nome as nome_fornecedor FROM produtos p 
                                        LEFT JOIN fornecedores f ON p.fornecedor = f.id 
                                        ORDER BY p.id DESC");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $nome = $res[$i]['nome'];
                        $categoria = $res[$i]['categoria'];
                        $fornecedor = $res[$i]['nome_fornecedor'];
                        $valor_compra = $res[$i]['valor_compra'];
                        $valor_venda = $res[$i]['valor_venda'];
                        $estoque = $res[$i]['estoque'];
                        $descricao = $res[$i]['descricao'];
                        $imagem = $res[$i]['imagem'];
                        $data_cadastro = $res[$i]['data_cadastro'];
                        $id = $res[$i]['id'];

                        ?>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $categoria ?></td>
                            <td><?php echo $fornecedor ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_compra, 2, ',', '.') ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_venda, 2, ',', '.') ?></td>
                            <td><?php echo $estoque ?></td>
                            <td><?php echo $descricao ?></td>
                            <td>
                                <?php if($imagem != "") { ?>
                                    <img src="../img/produtos/<?php echo $imagem ?>" width="50" height="50">
                                <?php } else { ?>
                                    <img src="../img/produtos/sem-foto.jpg" width="50" height="50">
                                <?php } ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($data_cadastro)) ?></td>



                            <td>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Registro";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM produtos where id = '" . $id2 . "' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    $nome2 = $res[0]['nome'];
                    $categoria2 = $res[0]['categoria'];
                    $fornecedor2 = $res[0]['fornecedor'];
                    $valor_compra2 = $res[0]['valor_compra'];
                    $valor_venda2 = $res[0]['valor_venda'];
                    $estoque2 = $res[0]['estoque'];
                    $descricao2 = $res[0]['descricao'];
                    $imagem2 = $res[0]['imagem'];
                    $data_cadastro2 = $res[0]['data_cadastro'];
                } else {
                    $titulo = "Inserir Registro";
                }


                ?>

                <h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Código</label>
                                <input value="<?php echo @$id2 ?>" type="text" class="form-control" id="id" name="id" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Nome">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Categoria</label>
                                <div class="input-group">
                                    <select name="categoria" class="form-control" id="categoria">
                                        <?php
                                        $query = $pdo->query("SELECT * FROM categorias order by nome desc ");
                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);

                                        for ($i = 0; $i < count($res); $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            }
                                            $nome_cat = $res[$i]['nome'];
                                            $id_cat = $res[$i]['id'];
                                        ?>
                                            <option <?php if (@$categoria2 == @$id_cat) { echo 'selected'; } ?> value="<?php echo $id_cat ?>"><?php echo $nome_cat ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCategoria">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <div class="input-group">
                                    <select name="fornecedor" class="form-control" id="fornecedor">
                                        <?php
                                        $query = $pdo->query("SELECT * FROM fornecedores order by nome desc ");
                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);

                                        for ($i = 0; $i < count($res); $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            }
                                            $nome_forn = $res[$i]['nome'];
                                            $id_forn = $res[$i]['id'];
                                        ?>
                                            <option <?php if (@$fornecedor2 == @$id_forn) { echo 'selected'; } ?> value="<?php echo $id_forn ?>"><?php echo $nome_forn ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFornecedor">
                                            <i class="fas fa-plus"><a href="../painel-adm/fornecedores.php"></a></i>
                                        </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor da Compra</label>
                                <input value="<?php echo @$valor_compra2 ?>" type="text" class="form-control" id="valor_compra" name="valor_compra" placeholder="Valor da Compra" onchange="calcularVenda()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Porcentagem de Lucro</label>
                                <input type="number" class="form-control" id="porcentagem" name="porcentagem" placeholder="%" value="" onchange="calcularVenda()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor da Venda</label>
                                <input value="<?php echo @$valor_venda2 ?>" type="text" class="form-control" id="valor_venda" name="valor_venda" placeholder="Valor da Venda">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estoque</label>
                                <input value="<?php echo @$estoque2 ?>" type="number" class="form-control" id="estoque" name="estoque" placeholder="Estoque">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea maxlength="255" class="form-control" name="descricao" id="descricao"><?php echo @$descricao2 ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Imagem</label>
                                <input type="file" value="<?php echo @$imagem2 ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg()" accept="image/jpeg,image/jpg,image/png,image/gif">
                            </div>

                            <div id="divImg">
                                <?php if(@$imagem2 != "") { ?>
                                    <img src="../img/produtos/<?php echo $imagem2 ?>" width="100" height="100" id="target">
                                <?php } else { ?>
                                    <img src="../img/produtos/sem-foto.jpg" width="100" height="100" id="target">
                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>









                    <small>
                        <div id="mensagem">

                        </div>
                    </small>

                </div>

                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$nome2 ?>" type="hidden" name="antigo" id="antigo">

                    <button type="button" id="btn-fechar" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>






<div class="modal" id="modal-deletar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <p>Deseja realmente Excluir este Registro?</p>

                <div align="center" id="mensagem_excluir" class="">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
                <form method="post">

                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>

                    <button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>





<?php

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}

?>



<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
<script type="text/javascript">
    $("#form").submit(function() {
        var pag = "<?= $pag ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: pag + "/inserir.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                $('#mensagem').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!!") {
                    $('#btn-salvar').prop('disabled', true);
                    $('#mensagem').addClass('text-success')
                    $('#mensagem').text(mensagem)
                    
                    // Habilita interação com o resto da página
                    $('.modal').css('pointer-events', 'auto');
                    
                    setTimeout(function() {
                        $('#btn-fechar').click();
                        window.location = "index.php?pag=" + pag;
                    }, 2000);
                } else {
                    $('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                }
            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function() {
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>





<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
    $(document).ready(function() {
        var pag = "<?= $pag ?>";
        $('#btn-deletar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/excluir.php",
                method: "post",
                data: $('form').serialize(),
                dataType: "text",
                success: function(mensagem) {
                    if (mensagem.trim() === 'Excluído com Sucesso!') {
                        $('#mensagem_excluir').addClass('text-success')
                        $('#mensagem_excluir').text(mensagem)
                        setTimeout(function() {
                            $('#btn-cancelar-excluir').click();
                            window.location = "index.php?pag=" + pag;
                        }, 2000); // 2 segundos de atraso
                    } else {
                        $('#mensagem_excluir').addClass('text-danger')
                        $('#mensagem_excluir').text(mensagem)
                    }
                },
            })
        })
    })
</script>



<!--SCRIPT PARA CARREGAR IMAGEM -->
<script type="text/javascript">
    function carregarImg() {
        var target = document.getElementById('target');
        var file = document.querySelector("input[type=file]").files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            target.src = "../img/produtos/sem-foto.jpg";
        }
    }
</script>





<!-- Adicione este script antes do fechamento do body -->
<script type="text/javascript">
    function calcularVenda() {
        var valor_compra = document.getElementById('valor_compra').value;
        var porcentagem = document.getElementById('porcentagem').value;
        
        // Remove formatação do valor de compra
        valor_compra = valor_compra.replace('.', '').replace(',', '.');
        
        if(valor_compra != "" && porcentagem != "") {
            var valor_compra_float = parseFloat(valor_compra);
            var porcentagem_float = parseFloat(porcentagem);
            
            var valor_venda = valor_compra_float + (valor_compra_float * (porcentagem_float/100));
            
            // Formata o valor de venda
            document.getElementById('valor_venda').value = valor_venda.toFixed(2).replace('.', ',');
            $('#valor_venda').mask('#.##0,00', {reverse: true});
        }
    }
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('#valor_compra').mask('R$ #.##0,00', {reverse: true});
        $('#valor_venda').mask('R$ #.##0,00', {reverse: true});
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTable').dataTable({
            "ordering": true
        })

    });
</script>


<!-- Modal Categoria -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-categoria">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome da Categoria</label>
                        <input type="text" class="form-control" id="categoria-nome" name="nome" required>
                    </div>
                    <div id="mensagem-categoria"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Fornecedor -->
<div class="modal fade" id="modalFornecedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Fornecedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-fornecedor">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome do Fornecedor</label>
                        <input type="text" class="form-control" id="fornecedor-nome" name="nome" required>
                    </div>
                    <div id="mensagem-fornecedor"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para Categoria e Fornecedor -->
<script type="text/javascript">
    $("#form-categoria").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'categoria/inserir.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(mensagem) {
                if(mensagem.trim() === "Salvo com Sucesso!!") {
                    $('#categoria-nome').val('');
                    $('#mensagem-categoria').addClass('text-success').text(mensagem);
                    setTimeout(function() {
                        $('#modalCategoria').modal('hide');
                        // Atualiza o select de categorias
                        $.get("categoria/listar.php", function(data) {
                            $("#categoria").html(data);
                        });
                    }, 2000);
                } else {
                    $('#mensagem-categoria').addClass('text-danger').text(mensagem);
                }
            }
        });
    });

    $("#form-fornecedor").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'fornecedores/inserir.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(mensagem) {
                if(mensagem.trim() === "Salvo com Sucesso!!") {
                    $('#fornecedor-nome').val('');
                    $('#mensagem-fornecedor').addClass('text-success').text(mensagem);
                    setTimeout(function() {
                        $('#modalFornecedor').modal('hide');
                        // Atualiza o select de fornecedores
                        $.get("fornecedores/listar.php", function(data) {
                            $("#fornecedor").html(data);
                        });
                    }, 2000);
                } else {
                    $('#mensagem-fornecedor').addClass('text-danger').text(mensagem);
                }
            }
        });
    });
</script>