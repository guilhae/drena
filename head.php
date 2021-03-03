<?php
ob_start();
require_once('pro/ligarbd.php');
ob_get_clean();
session_start();
$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));
if ($uti AND $uti['ati']==0){ echo "A tua conta foi desativada por um administrador."; session_destroy(); exit; } #Verificar se a conta está ativa
?>
<!doctype html>
<!-- Desenvolvido por Guilherme Albuquerque 2018/2021 -->
<html lang="pt">
	<head>
		<!-- Coisas básicas -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/png" href="imagens/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="estilo.css">
		<link rel="stylesheet" type="text/css" href="bootstrap.css">
		<meta name="description" content="Website de partilha de projetos, vídeo, música e imagens. Partilha o teu trabalho livremente na drena.">
		<title>drena</title>

		<!-- jQuery, jQuery form -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
		
		<!-- swup -->
		<script defer src="./node_modules/swup/dist/swup.min.js"></script>
		<script defer src="./node_modules/@swup/head-plugin/dist/SwupHeadPlugin.min.js"></script>
		<script defer src="./node_modules/@swup/scripts-plugin/dist/SwupScriptsPlugin.min.js"></script>
		<script defer src="swup.js"></script>

		<!-- Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script>
			$(function (){ $('[data-toggle="tooltip"]').tooltip() })
		</script>

		<!-- EditorJS -->
		<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
		<script src="https://cdn.jsdelivr.net/npm/editorjs-html@3.0.3/build/edjsHTML.js"></script>

		<!-- AnimeJS -->
		<script src="node_modules/animejs/lib/anime.min.js"></script>

		<!-- VideoJS -->
		<link href='node_modules/video.js/dist/video-js.css' rel='stylesheet'/>
		<script src='node_modules/video.js/dist/video.min.js'></script>
		<link href='node_modules/@silvermine/videojs-quality-selector/dist/css/quality-selector.css' rel='stylesheet'>
		<script src='node_modules/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js'></script>
		<link href="node_modules\@videojs\themes\fantasy\tema.css" rel="stylesheet" type="text/css">

		