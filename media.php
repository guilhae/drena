		<?php 
		require('head.php');
		$med = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med WHERE id='".$_GET["id"]."'"));

		if ($med){
			if ($med['tit']){$med_tit = $med['tit'];} else {$med_tit = $med['nom'];}															# Definir título
			$med_uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE id='".$med['uti']."'"));									# Utilizador dono
			$med_gos = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM med_gos WHERE med='".$_GET["id"]."' AND uti='".$uti['id']."';"));	# Informações do gosto do utilizador logado
		}
		?>
	</head>
	<body>
		<?php require('cabeçalho.php'); ?>
		<div id="swup" class="transition-fade">
		<?php
		if (!$med){
			echo "<h2 class='my-5 text-center'>"._('Media não encontrada!')." 😵</h2>‍";
			exit;
		} else {
			function tempoPassado($ptime){
				$etime = time() - $ptime; # Obtem o tempo que passou desde a publicação
				if ($etime < 1){ return '0 '._('segundos'); }
				$a = array( 31536000 => _('ano'),
							2592000 => _('mês'),
							604800 => _('semana'),
							86400 => _('dia'),
							3600 => _('hora'),
							60 => _('minuto'),
							1 => _('segundo')
							);
				$a_plural = array(
							_('ano') => _('anos'),
							_('mês') => _('mêses'),
							_('semana') => _('semanas'),
							_('dia') => _('dias'),
							_('hora') => _('horas'),
							_('minuto') => _('minutos'),
							_('segundo') => _('segundos')
							);
				foreach ($a as $secs => $str){
					$d = $etime / $secs;
					if ($d >= 1){
						$r = floor($d);
						return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
					}
				}
			}
			echo "
			<div class='p-0 my-0 offset-xl-3 col-xl-6 mt-0 mt-xl-4'>

				<section class='bg-dark shadow'>";
					switch ($med['tip']){
						case 1: # Vídeo
							$t_eliminar = _('Eliminar vídeo');
							$t_cor = 'primary';
							echo "<div class='mw-100'>
								<div style='position:relative;padding-bottom:56.25%;'>
									<iframe style='position:absolute;top:0;left:0;width:100%;height:100%;' src='/embed?id=".$med['id']."&titulo=0'></iframe>
								</div>
							</div>
							<div class='p-4'>";
							break;
						case 2: # Áudio
							$t_eliminar = _('Eliminar áudio');
							$t_cor = 'rosa';
							echo "<div class='p-4'>
							<iframe height='140px' class='w-100' src='/embed?id=".$med['id']."&titulo=0'></iframe>";
							break;
						case 3: # Imagem
							$t_eliminar = _('Eliminar imagem');
							$t_cor = 'ciano';
							echo "<div class='p-4'>
							<img class='w-100 mb-3' src='https://media.drena.xyz/img/".$med['id'].".".end(explode(".", $med['nom']))."'></img>";
							break;
					}
					echo "
						<div class='d-flex flex-row-reverse mb-3'>";
						if ($uti['id']==$med_uti['id']){
							if ($med['tip']=='1' OR $med['tip']=='2'){
								echo "
								<style>
								#thumb_a_carregar {
									text-align: center;
									padding: 0 20px;
									max-height: 24px;
								}
								.box {
									position: relative;
									width: 16px;
									height: 16px;
									margin: 4px;
									display: inline-block;
									background-color: #000;
								}
								</style>
								<label for='input_thu' role='button' class='btn btn-light ms-2' data-toggle='tooltip' data-placement='bottom' data-original-title=\""._('Alterar miniatura')."\">
									<span id='thumb_carregar'>
										<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image'/></svg>
									</span>
									<div id='thumb_a_carregar' style='display:none;' data-placement='bottom' data-toggle='tooltip' title=\""._('A carregar...')."\">
										<div class='box'></div>
									</div>
								</label>
								<form hidden enctype='multipart/form-data' action='#' method='post'>
									<input type='file' id='input_thu' name='thu' accept='image/*'/>
									<input type='submit'/>
								</form>
								<script>
								$('#input_thu').change(function(objEvent) {
									var objFormData = new FormData();
									var objFile = $(this)[0].files[0];
									objFormData.append('thu', objFile);
									$('#thumb_a_carregar').show();
									$('#thumb_carregar').hide();
									$.ajax({
										url: '/pro/med_thu.php?med=".$med['id']."',
										type: 'POST',
										contentType: false,
										data: objFormData,
										processData: false,
										success: function(output){
											if (output){
												alert(output);
											}
											location.reload();
										}
									});
								});
								anime({
									targets: '.box',
									keyframes: [
										{translateX: 16, rotate: '90deg'},
										{translateX: 0, rotate: '0deg'},
										{translateX: -16, rotate: '-90deg'},
										{translateX: 0, rotate: '0deg'}
									],
									duration: '3500',
									loop: true,
									easing: 'easeInOutBack',
									direction: 'normal'
								});
								</script>
								";
							}
							echo "
							<span data-toggle='modal' data-target='#modal_alerar_tit'>
								<button class='btn btn-light ms-2' data-toggle='tooltip' data-placement='bottom' data-original-title='"._('Alterar título')."'>
									<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#input-cursor-text'/></svg>
								</button>
							</span>
							<span data-toggle='modal' data-target='#modal_eliminar_med'>
								<button class='btn btn-light ml-1' data-toggle='tooltip' data-placement='bottom' data-original-title=\"".$t_eliminar."\">
									<svg class='bi' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#trash'/></svg>
								</button>
							</span>

							<!-- Modal Alterar título -->
							<div class='modal fade' id='modal_alerar_tit' tabindex='-1' role='dialog' aria-labelledby='modal_alerar_tit_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<form action='pro/med.php?ac=titulo&id=".$_GET['id']."' method='post'>
											<div class='modal-header'>
												<h2 class='modal-title' id='modal_alerar_tit_label'>"._('Alterar título')."<br></h2><br>
											</div>
											<div class='modal-body'>
												<input type='text' class='form-control' name='tit' placeholder='"._('Título')."' value='".$med_tit."'>
											</div>
											<div class='modal-footer text-end'>
												<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Fechar')."</button>
												<button type='submit' class='btn btn-".$t_cor." text-light'>"._('Alterar')."</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- Modal Eliminar -->
							<div class='modal fade' id='modal_eliminar_med' tabindex='-1' role='dialog' aria-labelledby='modal_eliminar_med_label' aria-hidden='true'>
								<div class='modal-dialog' role='document'>
									<div class='modal-content bg-dark bg-gradient rounded-xl shadow p-5 text-light'>
										<div class='modal-header'>
											<h2 class='modal-title' id='modal_eliminar_med_label'>".$t_eliminar."<br></h2><br>
										</div>
										<div class='modal-body'>
											<text><span class='h5'>".$med_tit."</span><br>"._('Esta ação é irreversível!')."</text>
										</div>
										<div class='modal-footer text-end'>
											<button type='button' class='btn btn-light' data-dismiss='modal'>"._('Cancelar')."</button>
											<a href='pro/med.php?ac=eliminar&id=".$_GET['id']."' role='button' class='btn btn-vermelho text-light'>"._('Eliminar')."</a>
										</div>
									</div>
								</div>
							</div>
							";
						}
							echo "
							<text class='h5 my-auto me-auto'>".$med_tit."</text>
						</div>
						<section class='mt-auto'>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<a href='/perfil?uti=".$med_uti['nut']."'><img src='fpe/".base64_encode($med_uti["fot"])."' class='rounded-circle' width='40'></a>
								</div>
								<div class='col d-flex'>
									<span class='justify-content-center align-self-center'>"._('Publicado por')." ".$med_uti['nut']."</span>
								</div>
							</div>
							<!--<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#bar-chart'/></svg>
								</div>
								<div class='col'>
									 visualizações
								</div>
							</div>-->
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg onclick='gosto()' class='bi' style='cursor:pointer;' width='1em' height='1em' fill='currentColor'>
										<use id='botao_gosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up-fill' "; if(!$med_gos){echo"hidden";} echo"/>
										<use id='botao_naogosto' xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#hand-thumbs-up' "; if($med_gos){echo"hidden";} echo"/>
									</svg>
								</div>
								<div class='col' >
									<span id='texto_gostos'>".$med['gos']."</span> "._('gostos')."
								</div>
							</div>
							<div class='row mb-1'>
								<div class='col-auto pr-0 text-center'>
									<svg class='bi' width='1em' height='1em' fill='currentColor'><use xlink:href='node_modules/bootstrap-icons/bootstrap-icons.svg#calendar4-week'/></svg>
								</div>
								<div class='col'>
									".sprintf(_('há %s'),tempoPassado(strtotime($med['den'])))."
								</div>
							</div>
						</section>
					</div>

				</section>

			</div>
			";
			if ($uti){
				echo "
				<script>
				function gosto(){
					$.ajax({
						url: 'pro/med_gos.php?id=".$med['id']."',
						success: function(result) {
							var gostos = +$('#texto_gostos').text();
							if (result==='true'){
								$('#botao_gosto').removeAttr('hidden');
								$('#botao_naogosto').attr('hidden', true);
								$('#texto_gostos').text(gostos+1);
							} else {
								$('#botao_gosto').attr('hidden', true);
								$('#botao_naogosto').removeAttr('hidden');
								$('#texto_gostos').text(gostos-1);
							}
						},
						error: function(){
							alert('Ocorreu um erro.');
						}
					});
				}
				</script>
				";
			} else {
				echo "
				<script>
				function gosto(){
					window.open('/entrar','_self');
				}
				</script>
				";
			}
		}
		?>
		</div>
	</body>
</html>