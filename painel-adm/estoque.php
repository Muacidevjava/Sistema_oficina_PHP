<?php
session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'admin') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "produtos";
$nivel_estoque = 1; // Defina o nível de estoque mínimo desejado
require_once("../conexao.php"); // Ensure this path is correct
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Produtos com Estoque Baixo</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <!-- <th>imagem</th> -->
                        <th>Referência</th>
                        <th>Categoria</th>
                        <th>Fornecedor</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mínimo</th>
                        <th>Valor de Venda</th>
                        <th>Valor de compra</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = $pdo->query("SELECT p.*, f.nome as nome_fornecedor, c.nome as nome_categoria 
                                        FROM produtos p 
                                        LEFT JOIN fornecedores f ON p.fornecedor = f.id 
                                        LEFT JOIN categorias c ON p.categoria = c.id
                                        WHERE p.estoque < $nivel_estoque
                                        ORDER BY p.estoque ASC");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        $nome = $res[$i]['nome'];
                        $categoria = $res[$i]['nome_categoria'];
                        $fornecedor = $res[$i]['nome_fornecedor'];
                        $valor_venda = $res[$i]['valor_venda'];
                        $valor_compra = $res[$i]['valor_compra'];
                        $estoque = $res[$i]['estoque'];
                        $id = $res[$i]['id'];
                        // $imagem = $res[$i]['imagem'];
                        ?>
                        <tr>
                            <td><?php echo $id ?></td>
                            <td class="text-danger">
                                <strong><?php echo $nome ?></strong>
                            </td>
                            <!-- <td class="text-center">
                                <?php if($imagem != "") { ?>
                                    <img src="../img/produtos/<?php echo $imagem ?>" width="50" height="50" class="img-thumbnail">
                                <?php } else { ?>
                                    <img src="../img/produtos/sem-foto.jpg" width="50" height="50" class="img-thumbnail">
                                <?php } ?>
                            </td> -->
                            <td><?php echo $res[$i]['ref'] ?></td>
                            <td><?php echo $categoria ?></td>
                            <td>
                                <a href="index.php?pag=fornecedores&funcao=info&id=<?php echo $res[$i]['fornecedor'] ?>" class="text-primary">
                                    <?php echo $fornecedor ?>
                                </a>
                            </td>
                            <td class="text-danger"><strong><?php echo $estoque ?></strong></td>
                            <td><?php echo $nivel_estoque ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_venda, 2, ',', '.') ?></td>
                            <td><?php echo 'R$ ' . number_format($valor_compra, 2, ',', '.') ?></td>
                            <td>
                                <a href="index.php?pag=produtos&funcao=pedido&id=<?php echo $id ?>" class='text-success mr-1' title='Fazer Pedidos'><i class='fas fa-plus'></i></a>
                                <!-- <a href="index.php?pag=produtos&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a> -->
                                <!-- <a href="index.php?pag=produtos&funcao=info&id=<?php echo $id ?>" class='text-primary mr-1' title='Descrição do Produto'><i class='fas fa-info-circle'></i></a> -->

                            </td>
                        </tr>
                    <?php } ?>





                </tbody>
            </table>
        </div>
</div>
</div>

<!-- Botão para abrir a modal de comparação -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalComparar">
    Comprar e Adicionar Produtos
</button>

<!-- Modal Comparar Produtos -->
<div class="modal fade" id="modalComparar" tabindex="-1" role="dialog" aria-labelledby="modalCompararLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCompararLabel">Comprar Produtos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Selecionar</th>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Valor Compra</th>
                                <th>Valor Venda</th>
                                <th>Estoque</th>
<th>Quantidade</th>
<th>Valor Compra</th>
<th>Valor Venda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $pdo->query("SELECT * FROM produtos WHERE estoque < $nivel_estoque ORDER BY nome ASC");
                            $produtos = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach ($produtos as $produto) {
                                echo '<tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="produto-check" value="'.$produto['id'].'">
                                    </td>
                                    <td>'.$produto['id'].'</td>
                                    <td>'.$produto['nome'].'</td>
                                    <td>R$ '.number_format($produto['valor_compra'], 2, ',', '.').'</td>
                                    <td>R$ '.number_format($produto['valor_venda'], 2, ',', '.').'</td>
                                    <td>'.$produto['estoque'].'</td>
<td><input type="number" class="form-control form-control-lg quantidade-input" min="1" value="1" style="width: 100px;"></td>
<td><input type="text" class="form-control form-control-lg valor-compra" value="'.number_format($produto['valor_compra'], 2, ',', '.').'" style="width: 120px;"></td>
<td><input type="text" class="form-control form-control-lg valor-venda" value="'.number_format($produto['valor_venda'], 2, ',', '.').'" style="width: 120px;"></td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="adicionarSelecionados()">Adicionar Selecionados</button>
            </div>
        </div>
    </div>
</div>

<script>
function adicionarSelecionados() {
    var selecionados = [];
    $('.produto-check:checked').each(function() {
        var id = $(this).val();
        var quantidade = $(this).closest('tr').find('.quantidade-input').val();
        var valor_compra = $(this).closest('tr').find('.valor-compra').val().replace('.','').replace(',','.');
        var valor_venda = $(this).closest('tr').find('.valor-venda').val().replace('.','').replace(',','.');
        
        selecionados.push({ 
            id: id, 
            quantidade: quantidade,
            valor_compra: valor_compra,
            valor_venda: valor_venda
        });
    });
    
    if(selecionados.length > 0) {
        $.ajax({
            url: '../painel-adm/estoque/inserir.php',
            type: 'POST',
            data: { produtos: selecionados, acao: 'adicionar_pedido' },
            success: function(response) {
                if(response == 'success') {
                    alert('Produtos adicionados com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao adicionar produtos: ' + response);
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição: ' + error);
            }
        });
    } else {
        alert('Selecione pelo menos um produto');
    }
}

function atualizarEstoque(id) {
    var estoque = $('#estoque_'+id).val();
    var valor_compra = $('#valor_compra_'+id).val();
    var valor_venda = $('#valor_venda_'+id).val();
    
    $.ajax({
        url: 'estoque/atualizar.php',
        type: 'POST',
        data: {
            acao: 'atualizar_estoque',
            id: id,
            estoque: estoque,
            valor_compra: valor_compra,
            valor_venda: valor_venda
        },
        success: function(response) {
            if(response == 'success') {
                alert('Produto atualizado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + response);
            }
        }
    });
}
</script>

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
                    $ref2 = $res[0]['ref'];
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Referência</label>
                                <div class="input-group">
                                    <input value="<?php echo @$ref2 ?>" type="text" class="form-control" id="ref" name="ref" placeholder="Referência do produto">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" onclick="lerCodigoBarras()">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
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
            <input value="<?php echo @$valor_compra2 ?>" type="text" class="form-control form-control-lg valor-compra" id="valor_compra" name="valor_compra" placeholder="Valor da Compra" onchange="calcularVenda()" style="font-size: 1.1rem; padding: 0.5rem;">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Porcentagem de Lucro</label>
            <input type="number" class="form-control form-control-lg" id="porcentagem" name="porcentagem" placeholder="%" value="<?php echo @$porcentagem ?>" onchange="calcularVenda()" style="font-size: 1.1rem; padding: 0.5rem;">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Valor da Venda</label>
            <input value="<?php echo @$valor_venda2 ?>" type="text" class="form-control form-control-lg valor-venda" id="valor_venda" name="valor_venda" placeholder="Valor da Venda" style="font-size: 1.1rem; padding: 0.5rem;">
        </div>
    </div>
</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estoque</label>
                                <input value="<?php echo @$estoque2 ?>" type="number" class="form-control" id="estoque" name="estoque" placeholder="Estoque" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea maxlength="1000" class="form-control" name="descricao" id="descricao"><?php echo @$descricao2 ?></textarea>
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





<!-- Modal Info -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalhes do Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if(@$_GET['funcao'] == 'info'){
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT p.*, f.descricao as descricao_fornecedor, f.nome as nome_fornecedor 
                                        FROM produtos p 
                                        LEFT JOIN fornecedores f ON p.fornecedor = f.id 
                                        WHERE p.id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome_produto = $res[0]['nome'];
                    $descricao_produto = $res[0]['descricao'];
                    $imagem2 = $res[0]['imagem'];
                    $nome_fornecedor = $res[0]['nome_fornecedor'];
                    $descricao_fornecedor = $res[0]['descricao_fornecedor'];
                    ?>
                    
                    <div class="text-center mb-3">
                        <?php if($imagem2 != "") { ?>
                            <img src="../img/produtos/<?php echo $imagem2 ?>" width="200" height="200" class="img-fluid">
                        <?php } else { ?>
                            <img src="../img/produtos/sem-foto.jpg" width="200" height="200" class="img-fluid">
                        <?php } ?>
                    </div>

                    <h6 class="text-primary"><?php echo $nome_produto ?></h6>
                    
                    <p><strong>Descrição do Produto:</strong></p>
                    <p class="text-muted"><?php echo $descricao_produto ?></p>
                    
                    <hr>
                    
                    <p><strong>Fornecedor:</strong> <?php echo $nome_fornecedor ?></p>
                    <p><strong>Descrição do Fornecedor:</strong></p>
                    <p class="text-muted"><?php echo $descricao_fornecedor ?></p>
                    
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "info") {
    echo "<script>$('#modalInfo').modal('show');</script>";
}
?>

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
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        
        // Initialize DataTable with options
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "ordering": true,
            "paging": true,
            "searching": true,
            "info": true
        });
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


<!-- Adicione este novo modal antes do fechamento do arquivo -->
<!-- Modal Similares -->
<div class="modal fade" id="modalSimilares" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Produtos da Mesma Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if(@$_GET['funcao'] == 'similares'){
                    $id_prod = $_GET['id'];
                    $id_cat = $_GET['categoria'];
                    
                    $query = $pdo->query("SELECT p.*, c.nome as nome_categoria 
                                        FROM produtos p 
                                        LEFT JOIN categorias c ON p.categoria = c.id 
                                        WHERE p.categoria = '$id_cat' AND p.id != '$id_prod'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($res) > 0){
                        $nome_categoria = $res[0]['nome_categoria'];
                        echo '<h6 class="mb-3">Categoria: '.$nome_categoria.'</h6>';
                        echo '<div class="row">';
                        
                        foreach($res as $produto){
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="text-center mt-2">
                                        <?php if($produto['imagem'] != "") { ?>
                                            <img src="../img/produtos/<?php echo $produto['imagem'] ?>" width="150" height="150" class="card-img-top">
                                        <?php } else { ?>
                                            <img src="../img/produtos/sem-foto.jpg" width="150" height="150" class="card-img-top">
                                        <?php } ?>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $produto['nome'] ?></h6>
                                        <p class="card-text">
                                            <strong>Valor:</strong> R$ <?php echo number_format($produto['valor_venda'], 2, ',', '.') ?><br>
                                            <strong>Estoque:</strong> <?php echo $produto['estoque'] ?>
                                        </p>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=info&id=<?php echo $produto['id'] ?>" class="btn btn-info btn-sm">Ver Detalhes</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        
                        echo '</div>';
                    } else {
                        echo '<p class="text-muted">Não há outros produtos nesta categoria.</p>';
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "similares") {
    echo "<script>$('#modalSimilares').modal('show');</script>";
}
?>

<script>
function lerCodigoBarras() {
    // Verifica se o navegador suporta a API de mídia
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Seu navegador não suporta a leitura de código de barras');
        return;
    }

    // Cria um elemento de vídeo temporário
    const video = document.createElement('video');
    const canvasElement = document.createElement('canvas');
    const canvas = canvasElement.getContext('2d');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true);
            video.play();

            requestAnimationFrame(function scan() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    
                    // Aqui você pode usar uma biblioteca de leitura de código de barras
                    // Por exemplo, usando a biblioteca QuaggaJS:
                    Quagga.decodeSingle({
                        decoder: {
                            readers: ["ean_reader", "ean_8_reader", "code_128_reader", "code_39_reader"]
                        },
                        locate: true,
                        src: canvasElement.toDataURL()
                    }, function(result) {
                        if(result && result.codeResult) {
                            document.getElementById('ref').value = result.codeResult.code;
                            stream.getTracks().forEach(track => track.stop());
                        } else {
                            requestAnimationFrame(scan);
                        }
                    });
                }
                requestAnimationFrame(scan);
            });
        });
}
</script>
<script>
$(document).ready(function() {
    // Máscara para valores monetários
    $('.valor-compra, .valor-venda').inputmask('decimal', {
        'alias': 'numeric',
        'groupSeparator': '.',
        'autoGroup': true,
        'digits': 2,
        'digitsOptional': false,
        'placeholder': '0',
        'radixPoint': ',',
        'prefix': 'R$ ',
        'rightAlign': false
    });
    
    // Ajustar tamanho dos inputs
    $('.quantidade-input, .valor-compra, .valor-venda').css({
        'font-size': '1.1rem',
        'padding': '0.5rem'
    });
});
</script>
<head>
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
</head>

<!-- Modal Info -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalhes do Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if(@$_GET['funcao'] == 'info'){
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT p.*, f.descricao as descricao_fornecedor, f.nome as nome_fornecedor 
                                        FROM produtos p 
                                        LEFT JOIN fornecedores f ON p.fornecedor = f.id 
                                        WHERE p.id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome_produto = $res[0]['nome'];
                    $descricao_produto = $res[0]['descricao'];
                    $imagem2 = $res[0]['imagem'];
                    $nome_fornecedor = $res[0]['nome_fornecedor'];
                    $descricao_fornecedor = $res[0]['descricao_fornecedor'];
                    ?>
                    
                    <div class="text-center mb-3">
                        <?php if($imagem2 != "") { ?>
                            <img src="../img/produtos/<?php echo $imagem2 ?>" width="200" height="200" class="img-fluid">
                        <?php } else { ?>
                            <img src="../img/produtos/sem-foto.jpg" width="200" height="200" class="img-fluid">
                        <?php } ?>
                    </div>

                    <h6 class="text-primary"><?php echo $nome_produto ?></h6>
                    
                    <p><strong>Descrição do Produto:</strong></p>
                    <p class="text-muted"><?php echo $descricao_produto ?></p>
                    
                    <hr>
                    
                    <p><strong>Fornecedor:</strong> <?php echo $nome_fornecedor ?></p>
                    <p><strong>Descrição do Fornecedor:</strong></p>
                    <p class="text-muted"><?php echo $descricao_fornecedor ?></p>
                    
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "info") {
    echo "<script>$('#modalInfo').modal('show');</script>";
}
?>

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
        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        
        // Initialize DataTable with options
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "ordering": true,
            "paging": true,
            "searching": true,
            "info": true
        });
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


<!-- Adicione este novo modal antes do fechamento do arquivo -->
<!-- Modal Similares -->
<div class="modal fade" id="modalSimilares" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Produtos da Mesma Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if(@$_GET['funcao'] == 'similares'){
                    $id_prod = $_GET['id'];
                    $id_cat = $_GET['categoria'];
                    
                    $query = $pdo->query("SELECT p.*, c.nome as nome_categoria 
                                        FROM produtos p 
                                        LEFT JOIN categorias c ON p.categoria = c.id 
                                        WHERE p.categoria = '$id_cat' AND p.id != '$id_prod'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($res) > 0){
                        $nome_categoria = $res[0]['nome_categoria'];
                        echo '<h6 class="mb-3">Categoria: '.$nome_categoria.'</h6>';
                        echo '<div class="row">';
                        
                        foreach($res as $produto){
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="text-center mt-2">
                                        <?php if($produto['imagem'] != "") { ?>
                                            <img src="../img/produtos/<?php echo $produto['imagem'] ?>" width="150" height="150" class="card-img-top">
                                        <?php } else { ?>
                                            <img src="../img/produtos/sem-foto.jpg" width="150" height="150" class="card-img-top">
                                        <?php } ?>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $produto['nome'] ?></h6>
                                        <p class="card-text">
                                            <strong>Valor:</strong> R$ <?php echo number_format($produto['valor_venda'], 2, ',', '.') ?><br>
                                            <strong>Estoque:</strong> <?php echo $produto['estoque'] ?>
                                        </p>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=info&id=<?php echo $produto['id'] ?>" class="btn btn-info btn-sm">Ver Detalhes</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        
                        echo '</div>';
                    } else {
                        echo '<p class="text-muted">Não há outros produtos nesta categoria.</p>';
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "similares") {
    echo "<script>$('#modalSimilares').modal('show');</script>";
}
?>

<script>
function lerCodigoBarras() {
    // Verifica se o navegador suporta a API de mídia
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Seu navegador não suporta a leitura de código de barras');
        return;
    }

    // Cria um elemento de vídeo temporário
    const video = document.createElement('video');
    const canvasElement = document.createElement('canvas');
    const canvas = canvasElement.getContext('2d');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true);
            video.play();

            requestAnimationFrame(function scan() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    
                    // Aqui você pode usar uma biblioteca de leitura de código de barras
                    // Por exemplo, usando a biblioteca QuaggaJS:
                    Quagga.decodeSingle({
                        decoder: {
                            readers: ["ean_reader", "ean_8_reader", "code_128_reader", "code_39_reader"]
                        },
                        locate: true,
                        src: canvasElement.toDataURL()
                    }, function(result) {
                        if(result && result.codeResult) {
                            document.getElementById('ref').value = result.codeResult.code;
                            stream.getTracks().forEach(track => track.stop());
                        } else {
                            requestAnimationFrame(scan);
                        }
                    });
                }
                requestAnimationFrame(scan);
            });
        });
}
</script>
<script>
$(document).ready(function() {
    // Máscara para valores monetários
    $('.valor-compra, .valor-venda').inputmask('decimal', {
        'alias': 'numeric',
        'groupSeparator': '.',
        'autoGroup': true,
        'digits': 2,
        'digitsOptional': false,
        'placeholder': '0',
        'radixPoint': ',',
        'prefix': 'R$ ',
        'rightAlign': false
    });
    
    // Ajustar tamanho dos inputs
    $('.quantidade-input, .valor-compra, .valor-venda').css({
        'font-size': '1.1rem',
        'padding': '0.5rem'
    });
});
</script>
<head>
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>