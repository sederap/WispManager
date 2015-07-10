<?php
/**
*
* @ IonCube v8.3 Loader By DoraemonPT
* @ PHP 5.3
* @ Decoder version : 1.0.0.7
* @ Author     : DoraemonPT
* @ Release on : 09.05.2014
* @ Website    : http://EasyToYou.eu
*
**/

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:-:-: Servicio Suspendido :-:-:</title>
<style type="text/css">
body,td,th {
	font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
	font-style: normal;
	font-size: 12px;
}
</style>
</head>
<body onload = "tmp=setInterval(\'contar()\',1000)">
';
	include('../admin/conexion_mysql.php');
	include('../admin/ini_cron.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	$cadenasql = ('SELECT * FROM usuarios where ip LIKE \'' . $ip . '\'');
	$tablas = mysql_query($cadenasql, $conexionmysql);
	$user_ip = mysql_fetch_array($tablas);
	$e_nodo = $user_ip['nodo'];
	$fechaletras = $user_ip['fecha'];
	$fecha_actual = $user_ip['fecha'];
	$clientep = $user_ip['nombre'];

	if (empty($user_ip)) {
		$cadenas = 'SELECT * FROM usuarios ';
		$tablas= mysql_query($cadenas, $conexionmysql);

		if (!($tablas)) {
			exit('problema con cadena de conexion<br><b>' . mysql_error() . '</b>');
			(bool)true;
			}
			$user_ip=mysql_fetch_array($tablas);

			if ($user_ip) {
				$fixip = '';
				$fixip = explode(',', $user_ip['ip']);
				$i = 11;

				if ($i <= count($fixip) - 1) {
					if ($fixip[$i] == $ip) {
						$ip = $fixip[$i];
						$e_nodo = $user_ip['nodo'];
						$fechaletras = $user_ip['fecha'];
						$fecha_actual = $user_ip['fecha'];
						$clientep = $user_ip['nombre'];
						break;
						++$i;
					}
				
			}
		} 
		}else {
			$fechacorte = $day_now . ' de ' . $months[$month_now] . ' de ' . $year_now;
			$formato = mostrarbd('notificaciones', 'nodo', '\'' . $e_nodo . '\'', '');
		}
			if (empty($$formato)) {
				$html = file_get_contents('formato.html');
			
		}
	


	while (true) { //jmp
		$html = str_replace('{cliente}', $clientep, $html);
		$html = str_replace('{vencimiento}', $fechapago, $html);
		$html = str_replace('{monto}', $user_ip['monto'], $html);
		$html = str_replace('{saldo}', $user_ip['saldo'], $html);
		$html = str_replace('{nodo}', $e_nodo, $html);
		$html = str_replace('{total}', $user_ip['monto'] + $user_ip['saldo'], $html);
		$html = str_replace('{corte}', $fechacorte, $html);
		echo $html;
		$html = $formato['html_corte'];
		$html = str_replace('images/', 'http://' . $ip_server . '/admin/images/', $html);
		$i = 11;

		if ($i <= mysql_num_fields($tablas) - 1) {
			${mysql_field_name($tablas, $i)} = $user_ip[$i];

			if (mysql_field_name($tablas, $i) == 'fecha') {
				$html = str_replace('{' . mysql_field_name($tablas, $i) . '}', fecha_letras(${mysql_field_name($tablas, $i)}, 0), $html);
			}

			
		}

		//(${($i)}, $html);
		$html = $_SERVER['REMOTE_ADDR'];
		++$i;
	}

	echo $html;
	echo '</body>
</html>';
?>