<?php
# Define o tempo para Portugal
date_default_timezone_set('Europe/Lisbon');

# Liga à base de dados
ob_start();
require_once('ligarbd.php');
ob_get_clean();
session_start();

# requerSessao (Padrão: 1)
if ($_SESSION["uti"]){
	$uti = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti WHERE nut='".$_SESSION["uti"]."'"));

	# Verificar se a conta está ativa
	if ($uti['ati']==0){ echo "A tua conta foi desativada por um administrador."; session_destroy(); exit; }

	$uti_mai = mysqli_fetch_assoc(mysqli_query($bd, "SELECT * FROM uti_mai WHERE id='".$uti['mai']."'"));
} else if ($funcoes['requerSessao']!=0){
	header("Location: /entrar.php");
	exit;
}

# Obtem a língua
function get_browser_language($available=['pt','en','de','it','fr'],$default='en') {
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if (empty($available)) {
        return $langs[ 0 ];
        }
		foreach ( $langs as $lang ){
			$lang = substr( $lang, 0, 2 );
			if( in_array( $lang, $available ) ) {
				return $lang;
			}
		}
	}
	return $default;
}
switch (get_browser_language()) {
	case 'pt': $locale = "pt_PT.UTF-8"; break;
    case 'en': $locale = "en_GB.UTF-8"; break;
    case 'de': $locale = "de_CH.UTF-8"; break;
    case 'it': $locale = "it_IT.UTF-8"; break;
    case 'fr': $locale = "fr_CH.UTF-8"; break;
}
setlocale(LC_ALL, $locale);
bindtextdomain("messages", "locale");
textdomain("messages");

# Número para cor
function numeroParaCor($num){
	switch ($num) {
		case 1: return 'azul'; break;
		case 2: return 'verde'; break;
		case 3: return 'amarelo'; break;
		case 4: return 'vermelho'; break;
		case 5: return 'rosa'; break;
		case 6: return 'ciano'; break;
		case 7: return 'primary'; break;
		default: return 'dark';
	}
}

# Encurtar nome
function encurtarNome($nome, $tamanho=25){
    if (strlen($nome)>=$tamanho){
        return (substr($nome, 0, $tamanho-2)."…");
    } else {
        return ($nome);
    }
}

# Notificações
if ($funcoes['notificacao']==1){
	function mandarNotificacao($not_uti_a,$not_uti_a_cod,$not_uti_b,$not_title,$not_icon,$not_body,$not_image){
		$not_post = '{
			"uti_a":"'.$not_uti_a.'",
			"uti_a_cod":"'.$not_uti_a_cod.'",
			"uti_b":"'.$not_uti_b.'",
			"notificacao":{
				"title":"'.$not_title.'",
				"icon":"'.$not_icon.'",
				"body": "'.$not_body.'",
				"image":"'.$not_image.'",
				"badge": "https://drena.pt/imagens/favicon.png",
				"actions": [
					{
					"action": "null",
					"title": "Ok"
					}
				]
			}
		}';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://drena.pt:3000/enviar");
		curl_setopt($ch, CURLOPT_POST, 1);
		$headers = array("content-type: application/json");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $not_post);
		curl_exec($ch);

	}
}
?>