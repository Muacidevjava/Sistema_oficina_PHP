<?php
//session_start();

// Verificação de acesso do administrador
if ($_SESSION['nivel_usuario'] == null || $_SESSION['nivel_usuario'] != 'admin') {
    echo "<script>window.location='../index.php'</script>";
}
$pag = "fornecedores";
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
    <a type="button" class="btn-danger btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Novo Fornecedor</a>
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
                        <th>TIPO PESSOA</th>
                        <th>CPF / CNPJ</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $query = $pdo->query("SELECT * FROM fornecedores order by id desc ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    for ($i = 0; $i < count($res); $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $nome = $res[$i]['nome'];
                        $tipo_pessoa = $res[$i]['tipo_pessoa'];
                        $cpf = $res[$i]['cpf'];
                        $telefone = $res[$i]['telefone'];
                        $endereco = $res[$i]['endereco'];
                        $email = $res[$i]['email'];
                        $id = $res[$i]['id'];





                    ?>


                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $tipo_pessoa ?></td>
                            <td><?php echo $cpf ?></td>
                            <td><?php echo $telefone ?></td>
                            <td><?php echo $endereco ?></td>
                            <td><?php echo $email ?></td>



                            <td>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=info&id=<?php echo $id ?>" class='text-info mr-1' title='Informações da Empresa'><i class='fas fa-info-circle'></i></a>
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

                    $query = $pdo->query("SELECT * FROM fornecedores where id = '" . $id2 . "' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);

                    $nome2 = $res[0]['nome'];
                    $tipo_pessoa2 = $res[0]['tipo_pessoa'];
                    $cpf2 = $res[0]['cpf'];
                    $telefone2 = $res[0]['telefone'];
                    $endereco2 = $res[0]['endereco'];
                    $email2 = $res[0]['email'];
                    $ibge2 = $res[0]['ibge'];
                    $descricao2 = $res[0]['descricao'];
                    $inscricao_estadual2 = $res[0]['inscricao_estadual'];
                    $inscricao_municipal2 = $res[0]['inscricao_municipal'];
                    $razao_social2 = $res[0]['razao_social'];
                    $nome_fantasia2 = $res[0]['nome_fantasia'];
                    $data_abertura2 = $res[0]['data_abertura'];
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome_mec" name="nome_mec" placeholder="Nome">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo Pessoa</label>
                                <select name="tipo_pessoa_mec" class="form-control" id="pessoa">
                                    <option value="Física" <?php if(@$tipo_pessoa2 == 'Física'){echo 'selected';}?>>Física</option>
                                    <option value="Jurídica" <?php if(@$tipo_pessoa2 == 'Jurídica'){echo 'selected';}?>>Jurídica</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" id="divcpf">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="<?php echo isset($tipo_pessoa2) && $tipo_pessoa2 == 'Física' ? @$cpf2 : ''; ?>" 
                                       type="text" 
                                       class="form-control" 
                                       id="cpf" 
                                       name="cpf_mec" 
                                       placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-6" id="divcnpj">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <div class="input-group">
                                    <input value="<?php echo isset($tipo_pessoa2) && $tipo_pessoa2 == 'Jurídica' ? @$cpf2 : ''; ?>" 
                                           type="text" 
                                           class="form-control" 
                                           id="cnpj" 
                                           name="cnpj_mec" 
                                           placeholder="CNPJ">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" onclick="consultarCNPJ()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input value="<?php echo @$telefone2 ?>" 
                                       type="text" 
                                       class="form-control" 
                                       id="telefone" 
                                       name="telefone_mec" 
                                       placeholder="(00) 00000-0000" 
                                       maxlength="15">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label>Endereço</label>
                        <input value="<?php echo @$endereco2 ?>" type="text" class="form-control" id="endereco" name="endereco_mec" placeholder="Endereço">
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Email</label>
                                <input value="<?php echo @$email2 ?>" type="text" class="form-control" id="email" name="email_mec" placeholder="email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Código IBGE</label>
                                <input value="<?php echo @$ibge2 ?>" type="text" class="form-control" id="ibge" name="ibge_mec" placeholder="Código IBGE" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Razão Social</label>
                                <input value="<?php echo @$razao_social2 ?>" type="text" class="form-control" id="razao_social" name="razao_social_mec" placeholder="Razão Social">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome Fantasia</label>
                                <input value="<?php echo @$nome_fantasia2 ?>" type="text" class="form-control" id="nome_fantasia" name="nome_fantasia_mec" placeholder="Nome Fantasia">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Inscrição Estadual</label>
                                <input value="<?php echo @$inscricao_estadual2 ?>" type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual_mec" placeholder="Inscrição Estadual">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Inscrição Municipal</label>
                                <input value="<?php echo @$inscricao_municipal2 ?>" type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal_mec" placeholder="Inscrição Municipal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data de Abertura</label>
                                <input value="<?php echo @$data_abertura2 ?>" type="date" class="form-control" id="data_abertura" name="data_abertura_mec">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descrição da Empresa</label>
                        <textarea class="form-control" id="descricao" name="descricao_mec" rows="3" placeholder="Descreva as principais atividades e informações da empresa"><?php echo @$descricao2 ?></textarea>
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
        // Check if table is already initialized
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').dataTable({
                "ordering": true
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // Verifica o tipo de pessoa ao carregar
        var tipoPessoa = $('#pessoa').val();
        if (tipoPessoa === 'Jurídica') {
            $('#divcnpj').show();
            $('#divcpf').hide();
        } else {
            $('#divcnpj').hide();
            $('#divcpf').show();
        }
        
        // Monitora mudanças no select
        $('#pessoa').change(function() {
            var value = $(this).val();
            if (value === 'Física') {
                $('#divcnpj').hide();
                $('#divcpf').show();
                $('#cnpj').val('');
            } else {
                $('#divcnpj').show();
                $('#divcpf').hide();
                $('#cpf').val('');
            }
        });
    });
</script>

<!-- Adicione este script antes do fechamento do body -->
<script type="text/javascript">
function consultarCNPJ() {
    var cnpj = $('#cnpj').val().replace(/[^0-9]/g, '');
    
    if(cnpj.length != 14){
        alert('CNPJ inválido');
        return;
    }

    $.ajax({
        url: 'https://www.receitaws.com.br/v1/cnpj/' + cnpj,
        method: 'GET',
        dataType: 'jsonp',
        success: function(data) {
            if(data.status == 'OK') {
                // Dados básicos
                $('#nome_mec').val(data.nome);
                $('#endereco').val(data.logradouro + ', ' + data.numero + ' - ' + data.bairro + ' - ' + data.municipio + '/' + data.uf);
                $('#telefone').val(data.telefone);
                $('#email').val(data.email);
                
                // Dados adicionais da empresa
                $('#razao_social').val(data.nome);
                $('#nome_fantasia').val(data.fantasia);
                $('#data_abertura').val(data.abertura.split('/').reverse().join('-')); // Converte dd/mm/aaaa para aaaa-mm-dd
                $('#descricao').val(data.atividade_principal[0].text); // Descrição da atividade principal
                
                // Buscar código IBGE pelo município
                $.ajax({
                    url: 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios',
                    method: 'GET',
                    data: {
                        nome: data.municipio
                    },
                    success: function(municipios) {
                        if (municipios && municipios.length > 0) {
                            // Filtra pelo estado correto
                            var municipioEncontrado = municipios.find(m => 
                                m.microrregiao.mesorregiao.UF.sigla === data.uf
                            );
                            
                            if (municipioEncontrado) {
                                $('#ibge').val(municipioEncontrado.id);
                            }
                        }
                    }
                });
            } else {
                alert('CNPJ não encontrado ou erro na consulta');
            }
        },
        error: function() {
            alert('Erro ao consultar o CNPJ. Tente novamente mais tarde.');
        }
    });
}

// Inicialização de máscaras y eventos
$(document).ready(function(){
    // Máscaras para documentos
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cpf').mask('000.000.000-00');
    
    // Máscara dinámica para teléfono
    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    };
    
    var spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
    
    $('#telefone').mask(SPMaskBehavior, spOptions);
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


<!-- Adicione este novo modal para informações -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informações da Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if(@$_GET['funcao'] == 'info'){
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT * FROM fornecedores where id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    $nome = $res[0]['nome'];
                    $tipo_pessoa = $res[0]['tipo_pessoa'];
                    $cpf = $res[0]['cpf'];
                    $telefone = $res[0]['telefone'];
                    $endereco = $res[0]['endereco'];
                    $email = $res[0]['email'];
                    $ibge = $res[0]['ibge'];
                    $descricao = $res[0]['descricao'];
                    $inscricao_estadual = $res[0]['inscricao_estadual'];
                    $inscricao_municipal = $res[0]['inscricao_municipal'];
                    $razao_social = $res[0]['razao_social'];
                    $nome_fantasia = $res[0]['nome_fantasia'];
                    $data_abertura = $res[0]['data_abertura'];
                    ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nome:</strong> <?php echo $nome ?></p>
                            <p><strong>Tipo Pessoa:</strong> <?php echo $tipo_pessoa ?></p>
                            <p><strong><?php echo $tipo_pessoa == 'Física' ? 'CPF' : 'CNPJ' ?>:</strong> <?php echo $cpf ?></p>
                            <p><strong>Telefone:</strong> <?php echo $telefone ?></p>
                            <p><strong>Email:</strong> <?php echo $email ?></p>
                            <p><strong>Endereço:</strong> <?php echo $endereco ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Razão Social:</strong> <?php echo $razao_social ?></p>
                            <p><strong>Nome Fantasia:</strong> <?php echo $nome_fantasia ?></p>
                            <p><strong>Inscrição Estadual:</strong> <?php echo $inscricao_estadual ?></p>
                            <p><strong>Inscrição Municipal:</strong> <?php echo $inscricao_municipal ?></p>
                            <p><strong>Data de Abertura:</strong> <?php echo date('d/m/Y', strtotime($data_abertura)) ?></p>
                            <p><strong>Código IBGE:</strong> <?php echo $ibge ?></p>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p><strong>Descrição da Empresa:</strong></p>
                            <p><?php echo $descricao ?></p>
                        </div>
                    </div>
                    
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
        $('#dataTable').DataTable({
            destroy: true,
            ordering: true
        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        // Verifica o tipo de pessoa ao carregar
        var tipoPessoa = $('#pessoa').val();
        if (tipoPessoa === 'Jurídica') {
            $('#divcnpj').show();
            $('#divcpf').hide();
        } else {
            $('#divcnpj').hide();
            $('#divcpf').show();
        }
        
        // Monitora mudanças no select
        $('#pessoa').change(function() {
            var value = $(this).val();
            if (value === 'Física') {
                $('#divcnpj').hide();
                $('#divcpf').show();
                $('#cnpj').val('');
            } else {
                $('#divcnpj').show();
                $('#divcpf').hide();
                $('#cpf').val('');
            }
        });
    });
</script>

<!-- Adicione este script antes do fechamento do body -->
<script type="text/javascript">
function consultarCNPJ() {
    var cnpj = $('#cnpj').val().replace(/[^0-9]/g, '');
    
    if(cnpj.length != 14){
        alert('CNPJ inválido');
        return;
    }

    $.ajax({
        url: 'https://www.receitaws.com.br/v1/cnpj/' + cnpj,
        method: 'GET',
        dataType: 'jsonp',
        success: function(data) {
            if(data.status == 'OK') {
                // Dados básicos
                $('#nome_mec').val(data.nome);
                $('#endereco').val(data.logradouro + ', ' + data.numero + ' - ' + data.bairro + ' - ' + data.municipio + '/' + data.uf);
                $('#telefone').val(data.telefone);
                $('#email').val(data.email);
                
                // Dados adicionais da empresa
                $('#razao_social').val(data.nome);
                $('#nome_fantasia').val(data.fantasia);
                $('#data_abertura').val(data.abertura.split('/').reverse().join('-')); // Converte dd/mm/aaaa para aaaa-mm-dd
                $('#descricao').val(data.atividade_principal[0].text); // Descrição da atividade principal
                
                // Buscar código IBGE pelo município
                $.ajax({
                    url: 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios',
                    method: 'GET',
                    data: {
                        nome: data.municipio
                    },
                    success: function(municipios) {
                        if (municipios && municipios.length > 0) {
                            // Filtra pelo estado correto
                            var municipioEncontrado = municipios.find(m => 
                                m.microrregiao.mesorregiao.UF.sigla === data.uf
                            );
                            
                            if (municipioEncontrado) {
                                $('#ibge').val(municipioEncontrado.id);
                            }
                        }
                    }
                });
            } else {
                alert('CNPJ não encontrado ou erro na consulta');
            }
        },
        error: function() {
            alert('Erro ao consultar o CNPJ. Tente novamente mais tarde.');
        }
    });
}

// Inicialização de máscaras y eventos
$(document).ready(function(){
    // Máscaras para documentos
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cpf').mask('000.000.000-00');
    
    // Máscara dinámica para teléfono
    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    };
    
    var spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
    
    $('#telefone').mask(SPMaskBehavior, spOptions);
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
