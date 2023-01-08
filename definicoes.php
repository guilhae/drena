	<?php 
	require('head.php');
	if (!$uti){
		header("Location: /entrar.php"); exit;
	}
	?>
		<script src='/js/api.min.js'></script>
		<script src='/js/notificacoes.js'></script>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div class="shadow p-0 my-0 my-xl-4 col-xl-6 offset-xl-3">
			<div class="p-xl-5 p-4 bg-dark text-light">

				<div class="row">
					<div class='col-3 col-xl-3 col-lg-2 col-md-2 col-sm-4 pe-sm-3 pe-0'>
						<img class="rounded-circle img-fluid" src='<?php echo $url_media."fpe/".$uti['fpe'].".jpg"; ?>'>
					</div>
					<div class="col-9 col-xl-9 col-lg-10 col-md-10 col-sm-8 row pe-0">
						<div class='col-12 col-sm-8 pe-0'>
							<h2><?php echo $uti["nut"];?></h2>
							<text class='h5'><?php echo $uti["nco"];?></text><br>
							<text><?php echo _('Criação da conta').": ".substr($uti["dcr"], 0, -9);?></text>
						</div>
						<div class='col-12 col-sm-4 p-sm-0 pt-2'>
							<a class="float-sm-end btn btn-light" href='/pro/sair'><?php echo _('Sair');?> <i class="bi bi-box-arrow-right"></i></a>
						</div>
					</div>
				</div>

			</div>
			<div class="p-xl-5 p-4 bg-light text-dark">
				<h2 class='pb-3'>Definições</h2>

				
				<?php
				/*echo "Idioma
				<div class='row'>
					<div class='col-4 mr-auto'>
						<select class='form-control text-dark border-dark' style='cursor:pointer;'>
							<option>Deutsch</option>
							<option>English</option>
							<option>Français</option>
							<option>Italiano</option>
							<option selected>Português</option>
						</select>
					</div>
					<div class='col-auto p-0'><button class='btn btn-dark'>"._('Guardar')."</button></div>
				</div>";*/

				if ($uti['rno']==1){
					$uti_rno = 'checked';
				}

				echo "
				<div class='mb-4'>
					Email<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='".$uti_mai['mai']."'>
							
						</div>
						<div class='col-auto p-0'><a href='/registo?ac=alterarMail' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>
				
				
				<div class='mb-4'>
					"._('Palavra-passe')."<br>
					<div class='row'>
						<div class='col-6 col-sm-4 mr-auto'>
							<input class='form-control text-dark border-0 disabled' disabled value='••••••••••••'>
							
						</div>
						<div class='col-auto p-0'><a href='/entrar?ac=alterarPasse&uti=".$uti['nut']."&cod=".$uti_mai['cod']."' role='button' class='btn btn-dark'>"._('Alterar')."</a></div>
					</div>
				</div>

				<hr>

				<div class='my-4'>
					<div class='form-check form-switch'>
						<input type='checkbox' role='switch' class='form-check-input' ".$uti_rno." id='switch_not_uti'>
						<label class='form-check-label' for='switch_not_uti'>
						Receber notificações
						</label>
					</div>

					<div class='form-check form-switch'>
                        <input type='checkbox' role='switch' class='form-check-input' id='switch_not_sub'>
                        <label class='form-check-label' for='switch_not_sub'>
						Notificações subscritas neste dispositivo
						</label>
                    </div>
				</div>
				
				<div id='info_not' class='alert d-flex align-items-center justify-content-between' role='alert'></div>
				";
				?>
				<script>
				console.debug("Notification.premission: "+Notification.permission);
				
				function Not_denied(){
					$('#info_not').html('<span><i class="bi bi-exclamation-triangle-fill"></i> As notificações estão bloqueadas, ative nas definições do Browser</span>');
					$('#info_not').addClass('alert-vermelho');
					$('#info_not').removeClass('alert-dark');
					$('#notificacoes').prop('disabled', true);
				}

				function Not_ask(){
					$('#info_not').html('<span><i class="bi bi-info-circle-fill"></i> As notificações estão inativas neste Browser</span><button id="btn_not_ati" class="btn btn-primary m-0">Ativar</button>');
					$('#info_not').addClass('alert-dark');
				}

				function Not_granted(){
					$('#info_not').html('<span><i class="bi bi-check-circle-fill"></i> As notificações estão ativas neste Browser</span>');
					$('#info_not').addClass('alert-primary');
					$('#info_not').removeClass('alert-dark');
					$('#notificacoes').prop('checked', true);
					$('#notificacoes').prop('disabled', true);
					//Obtem se está subscrito ou não
					not_sub("ob").then((res) => {
						console.debug("Está subscrito? "+res);
						if (res=='true'){
							$('#switch_not_sub').prop('checked', true);
						}
					});
				}

				if (!('Notification' in window)) {
					console.warn('This browser does not support desktop notification');
					$('#notificacoes').prop('disabled', true);
				} else if (Notification.permission === 'default') {
					Not_ask();
				} else if (Notification.permission === 'granted') {
					Not_granted();
				} else {
					Not_denied();
				}

				$('#btn_not_ati').click(function() {
					Notification.requestPermission().then((permission) => {
						if (permission === 'denied') {
							Not_denied();
						} else if (permission === 'granted') {
							Not_granted();
						}
					});
				});

				$('#switch_not_uti').change(function() {
					result = api('not',{'ac':'receber'});
					console.debug('Receber notificações: '+result['est']);
				});

				$('#switch_not_sub').change(function() {
					not_sub("ac").then((res) => {
						if (res=='true'){
							$('#switch_not_sub').prop('checked', true);
						} else {
							$('#switch_not_sub').prop('checked', false);
						}
					});
				});
				</script>
				
			</div>
		</div>
	</body>
</html>