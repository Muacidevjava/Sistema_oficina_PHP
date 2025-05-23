<?php
session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'admin') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "compras";
require_once("../conexao.php");

// Verificação adicional no script de exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['funcao']) && $_POST['funcao'] == 'excluir' && $_SESSION['nivel_usuario'] == 'admin') {
    $id = $_POST['id'];
    $query = $pdo->prepare("DELETE FROM compras WHERE id = :id");
    $query->bindValue(":id", $id);
    $query->execute();
    echo 'Excluído com Sucesso!';
} else {
    echo 'Acesso negado!';
}
?>

<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Funcionario</th>
                        <th>Data Compra</th>
                        <th>Ações</th>
                        
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT compras.*, usuarios.nome AS nome_funcionario, produtos.nome AS nome_produto FROM compras JOIN usuarios ON compras.funcionario = usuarios.id JOIN produtos ON compras.id_produto = produtos.id ORDER BY compras.id DESC");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $descricao = $res[$i]['descricao'] . " - " . $res[$i]['nome_produto'];
                        $valor = $res[$i]['valor'];
                        $nome_funcionario = $res[$i]['nome_funcionario'];
                        $data_compra = $res[$i]['data_compra'];
                        $id = $res[$i]['id'];

                        // Formatar valor para exibição
                        $valor_formatado = 'R$ ' . number_format($valor, 2, ',', '.');
                        
                        // Formatar data para exibição
                        $data_formatada = date('d/m/Y H:i', strtotime($data_compra));

                    ?>

                        <tr>
                            <td><?php echo $descricao ?></td>
                            <td><?php echo $valor_formatado ?></td>
                            <td><?php echo $nome_funcionario ?></td>
                            <td><?php echo $data_formatada ?></td>
                            <td>
                                <?php if ($_SESSION['nivel_usuario'] == 'admin') { ?>
                                    <button class='btn-delete text-danger mr-1' data-id="<?php echo $id ?>" title='Excluir Registro'><i class='far fa-trash-alt'></i></button>
                                <?php } ?>
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

                    $query = $pdo->query("SELECT * FROM mecanicos where id = '" . $id2 . "' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    $nome2 = $res[0]['nome'];
                    $cpf2 = $res[0]['cpf'];
                    $telefone2 = $res[0]['telefone'];
                    $endereco2 = $res[0]['endereco'];
                    $email2 = $res[0]['email'];
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="<?php echo @$cpf2 ?>" type="text" class="form-control" id="cpf" name="cpf_mec" placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input value="<?php echo @$telefone2 ?>" type="text" class="form-control" id="telefone" name="telefone_mec" placeholder="Telefone">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label>Endereço</label>
                        <input value="<?php echo @$endereco2 ?>" type="text" class="form-control" id="endereco" name="endereco_mec" placeholder="Endereço">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="<?php echo @$email2 ?>" type="text" class="form-control" id="email" name="email_mec" placeholder="email">
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
                    $('#mensagem').addClass('text-success')
                    $('#mensagem').text(mensagem)
                    setTimeout(function() {
                        $('#btn-fechar').click();
                        window.location = "index.php?pag=" + pag;
                    }, 2000); // 2 segundos de atraso
                } else {
                    $('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                }

                $('#mensagem').text(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function() {
                        /* faz alguma coisa durante o progresso do upload */
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

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTable').dataTable({
            "ordering": true
        })

    });
</script>





<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-delete').click(function(event) {
            event.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: 'compras/excluir.php',
                method: 'GET',
                data: {id: id},
                success: function(response) {
                    alert(response);
                    window.location.reload();
                },
                error: function() {
                    alert('Erro ao excluir o registro.');
                }
            });
        });
    });
</script>



<small>
    <div id="mensagem">
        <!-- Mensagem de sucesso ou erro será exibida aqui -->
    </div>
</small>

<script type="text/javascript">
    $(document).ready(function() {
        // Função para ocultar a mensagem após 3 segundos
        function ocultarMensagem() {
            setTimeout(function() {
                $('#mensagem').fadeOut('slow');
            }, 3000); // 3000 milissegundos = 3 segundos
        }

        // Chama a função para ocultar a mensagem após a exclusão
        $('#btn-deletar').click(function(event) {
            event.preventDefault();

            $.ajax({
                url: pag + "/excluir.php",
                method: "post",
                data: $('form').serialize(),
                dataType: "text",
                success: function(mensagem) {
                    $('#mensagem_excluir').text(mensagem);
                    if (mensagem.trim() === 'Excluído com Sucesso!') {
                        $('#mensagem_excluir').addClass('text-success');
                        setTimeout(function() {
                            $('#btn-cancelar-excluir').click();
                            window.location = "index.php?pag=" + pag;
                        }, 2000); // 2 segundos de atraso
                    } else {
                        $('#mensagem_excluir').addClass('text-danger');
                    }
                    ocultarMensagem(); // Oculta a mensagem após 3 segundos
                },
            });
        });
    });
</script>

