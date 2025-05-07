<?php
session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'admin') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "categoria";
require_once("../conexao.php");
/*
@session_start();
    //verificar se o usuário está autenticado
if(@$_SESSION['id_usuario'] == null || @$_SESSION['nivel_usuario'] != 'Admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";

}
    */


?>

<div class="row mt-4 mb-4">
    <a type="button" class="btn-danger btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Nova Categoria</a>
    <a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none" href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT * FROM categorias order by id desc ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $nome = $res[$i]['nome'];

                        $id = $res[$i]['id'];

                        $query_tot = $pdo->query("SELECT * FROM produtos where categoria = $id");
                        $res_tot = $query_tot->fetchAll(PDO::FETCH_ASSOC);
                        $total_produtos = @count($res_tot);



                    ?>


                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo @$total_produtos . " Produtos" ?></td>
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
                $('#mensagem').removeClass();

                if (mensagem.trim() == "Salvo com Sucesso!") {
                    $('#mensagem').addClass('text-success');
                    $('#mensagem').text(mensagem);

                    // Fechar o modal após 2 segundos
                    setTimeout(function() {
                        $('#btn-fechar').click();
                        window.location = "index.php?pag=" + pag;
                    }, 2000);
                } else {
                    $('#mensagem').addClass('text-danger');
                    $('#mensagem').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false
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