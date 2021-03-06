<?php
    $acao = 'recuperarTarefasPendentes';
    require_once 'PHP_SCRIPTS/Tarefa_controller.php';

    //Tudo que está acima de um require é incluido no escopo do arquivo requerido
    //Portanto, a variável $acao pode ser acessada no Tarefa_controller.php
?>

<?php
    // import -> produz somente um warning caso o arquivo não exista, ou seja, o resto do programa é executado
    // requiere -> produz um fatal error caso o arquivo não exista, ou seja, o resto do programa não é executad

    // import_once -> se o script já tiver sido incluido não será incluido de novo
    // requiere_once -> se o script já tiver sido incluido não será incluido de novo
    require_once "../02_ACESSOS__PHP/validador_acesso.php";// o require once é utilizado porque se o include once fosse utilizado ocorreria somente um warning, com isso a página que não era pra ser exibida será exibida
    // o require_once é utilizado porque a validação ocorreŕa somente uma vez, no contexto atual a possibilidade de validar mais de uma vez não faz sentido

    if(isset($_SESSION['autenticado']) && $_SESSION['perfil_id'] == 1) {
         header('Location: ../07_LOGIN_&&_HOME/home.php');
    }
?>

<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Nubank - Pagamentos pendentes</title>

		<link rel="stylesheet" href="CSS/estilo.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
              integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
              integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

        <!-- Estilo Customizado -->
        <link rel="stylesheet" type="text/css" href="../03___CSS/header.css">


        <!-- Icone do site -->
        <link rel="shortcut icon" href="../06_IMG/logo.ico" >

        <!--
         Integrity: Verifica a integridade do arquivo, ou seja, verifica se o arquivo foi corrompido. Se as hash
         criptograficas forem diferentes o arquivo não será baixado.
         O sha-384 é um resumo criptográfico da família SHA-2, enfim é uma hash criptografia. Ese recurso é sensível
         a qualquer alteração, ou seja qualquer alteração no arquivo, mesmo minima, gerará uma hash diferente.

         Crossorigin: Habilita o recebimento de informações de erros de execução.
         A palavra-chave "anônimo" significa que não haverá troca de credenciais do usuário por meio de cookies,
         certificados SSL do cliente ou autenticação HTTP-->

        <script>
            function editar(id, txt_tarefa) {
                //Cria um form de edição
                let form = document.createElement('form');
                form.action = "PHP_SCRIPTS/Tarefa_controller.php?pag=index&acao=atualizar";
                form.method = 'post';
                form.className ='row';

                //Cria um input para entrada do texto
                let inputTarefa = document.createElement('input');
                inputTarefa.type = 'text';
                inputTarefa.name = 'tarefa';
                inputTarefa.className = 'col-10 offset-1 form-control mb-2';
                inputTarefa.value = txt_tarefa;

                //Cria um input hidden (oculto) para guardar o id da tarefa
                let inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id';
                inputId.value = id;

                //Cria um button para envio do form
                let button = document.createElement('button');
                button.type = 'submit';
                button.className = 'col-10 offset-1 btn btn-info';
                button.innerHTML = 'Atualizar';

                //Inclui o inputTarefa no form
                form.appendChild(inputTarefa);

                //Inclui o inputId no form
                form.appendChild(inputId);

                //Inclui o button no form
                form.appendChild(button);

                //Seleciona a div tarefa
                let tarefa = document.getElementById('tarefa_'+id);

                //Limpar o texto da div tarefa para inclusão do form
                tarefa.innerHTML = '';

                //Incluir form na div tarefa
                tarefa.appendChild(form);

            }

        </script>

    </head>

	<body>

    <!-- header -->
    <?php
        require_once "../07_LOGIN_&&_HOME/header.php";
    ?>

		<div class="container mt-4">
			<div class="row">
				<div class="col-md-3 mb-4">
					<ul class="list-group">
						<li class="list-group-item active"><a href="#">Pagamentos pendentes</a></li>
						<li class="list-group-item"><a href="PHP_PAGES/nova_tarefa.php">Novo lembrete</a></li>
						<li class="list-group-item"><a href="PHP_PAGES/todas_tarefas.php">Todos lembretes </a></li>
					</ul>
				</div>

				<div class="col-md-9">
					<div id = "pagina" class="container">
						<div class="row">
							<div class="col">
								<h4 class="roxo">Pagamentos pendentes</h4>
								<hr />

                                    <?php foreach ($tarefas_bd as $indice => $tarefa_bd){ ?>
                                        <div class="row mb-3 tarefa">
                                            <div id="tarefa_<?= $tarefa_bd->id?>"class="col-12 text-center">
                                                <?= $tarefa_bd->tarefa?> (<?= $tarefa_bd->status ?>)
                                            </div>

                                            <div class="col-12 mt-2 d-flex justify-content-between">

                                                    <form action="PHP_SCRIPTS/Tarefa_controller.php?pag=index&acao=remover" method="post">
                                                        <button type="submit" class=" btn fas fa-trash-alt fa-lg text-danger" style="background-color:  #8A05BE;"></button>
                                                        <input name = 'id' value="<?= $tarefa_bd->id ?>" type="hidden">
                                                    </form>

                                                    <div>
                                                        <button class="btn fas fa-edit fa-lg text-info"  style="background-color:  #8A05BE;" onclick="editar( <?= $tarefa_bd->id?>,'<?= $tarefa_bd->tarefa?>')"></button>
                                                    </div>

                                                    <form action="PHP_SCRIPTS/Tarefa_controller.php?pag=index&acao=realizado" method="post">
                                                        <button class="btn fas fa-check-square fa-lg text-success" style="background-color:  #8A05BE;"></button>
                                                        <input name = 'id' value="<?= $tarefa_bd->id ?>" type="hidden">
                                                    </form>

                                            </div>
                                        </div>

                                    <?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../04_BOOTSTRAP_4/js/bootstrap.min.js"></script>

    </body>
</html>