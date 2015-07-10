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

	echo '<style type="text/css">
#accept{
	text-align: center;
	color: #000;
	border: 1px solid;
	border-color: #396;
	width: 90%;
	background-color: #DEF8D6;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 13px;
}

#err{
	text-align: center;
	color: #F00;
	border: 1px solid;
	border-color: #C30;
	width: 90%;
	background-color: #FFE6E6;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 13px;
}
</style>
';
	require( '../admin/conexion_mysql.php' );
	include( 'class.correo.php' );
	include( 'class.smtp.php' );
	$id_nodo = $_POST['nodo'];
	$cadena2 = ('SELECT * FROM notificaciones where nodo LIKE \'' . $id_nodo . '\'' );
	$tabla2 = mysql_query( $cadena2, $conexionmysql );

	if (!( $tabla2)) {
		exit( 'problema con cadena de conexion<br><b>' . mysql_error(  ) . '</b>' );
		(bool)true;
		$config_server = mysql_fetch_array( $tabla2 );

		if (empty( $_POST['correo'] )) {
			echo '<div id="err"><img src="images/warning.png" width="32" height="32"> Por Favor debe ingresar Su correo Electronico.<br /></div>';
			return 1;
		}


		if (empty( $_POST['operacion'] )) {
			echo '<div id="err"><img src="images/warning.png" width="32" height="32"> Por Favor debe ingresar N° de operacion del Boucher.<br /></div>';
			return 1;}

			if (empty( $_POST['monto'] )) {
				echo '<div id="err"><img src="images/warning.png" width="32" height="32"> Por Favor debe ingresar El monto exacto de su pago.<br /></div>';
				return 1;}

				if (empty( $_POST['fechapago'] )) {
					echo '<div id="err"><img src="images/warning.png" width="32" height="32"> Por Favor debe Indique la fecha de Pago.<br /></div>';
					return 1;
					echo '<div id="accept">Su pago fue enviado correctamente.<br/>Gracias por reportar su Pago. </div>';
					$dar_enters = str_replace( '', '<br>', $_POST['comentario'] );
					$dar_espacops = str_replace(' ', '&nbsp; ', $dar_enters );
					
				}
			
		

		
		
		$comentario_ok = $lugar;
		$nombre = $_POST['nombre'];
		$asunto = 'Reporte Pago';
		$fecha = $_POST['fechapago'];
		$lugar = $_POST['banco'];
		$forma = $_POST['tipopago'];
		$operacion = $_POST['operacion'];
		$monto = $_POST['monto'];
		$mensaje = $forma;
		$nodo = $_POST['nodo'];
		$fecha2 = date( 'Y-m-d' );
		mysql_query('insert into reporte (nombre,asunto,fecha,lugar,forma,operacion,monto,mensaje,reporte,nodo,estado,action,fecha2) values (\'' . $nombre . '\',\'' . $asunto . '\',\'' . $fecha . '\',\'' . $lugar . '\',\'' . $forma . '\',\'' . $operacion . '\',\'' . $monto . '\',\'' . $mensaje . '\',\'Pagina de corte\',\'' . $nodo . '\',\'NUEVO\',\'notifica\',\'' . $fecha2 . '\')', $conexionmysql );
		$mensaje = '<html>
	<head>
		<title></title>
	</head>
	<body>
		<div style="font-weight: bold;width: 100%;height: 50px;background: #086A87;filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr=#0B3861, endColorstr=#086A87);	background: -webkit-gradient(linear, left top, left bottom, from(#0B3861), to(#086A87));background: -moz-linear-gradient(top,#0B3861,#086A87);margin-bottom: 2px;border-radius: 4px;-webkit-border-radius: 4px;-moz-border-radius: 4px;padding: 15px 20px 0 20px;">
			<span style="font-size: 24px;color: #FFF;font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">Mikrowisp Manager</span>
			<div style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif; font-size: 12px; width: 50%; color: #FFF;">
				Reporte de Pago</div>
		</div>
		<div style="font-family: Trebuchet MS, Arial, Helvetica, sans-serif;font-size: 13px;padding-left: 20px;width: 100%;text-align: justify;color: #333;padding-right: 15px;">
			<p style="font-weight: bold;font-size: 14px;color: #069;">
				Cliente(a) : ' . $_POST['nombre'] . '</p>
			<fieldset style="width:360px; display:block; padding-left:0px; margin-bottom:0.5em; border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border: 2px solid #069; margin-top:2px; float: left; background-color: #EEF5FF; margin-right: 20px;">
				<legend style="font-size:12px;border:1px solid #01A9DB;margin:5px 0 0 15px;padding:2px 2px 2px 20px;width:150px;background-color: #01A9DB;border-radius: 7px;	-webkit-border-radius: 7px;	-moz-border-radius: 7px;-webkit-box-shadow: 0 1px 1px;-moz-box-shadow: 0 1px 1px;color: #fff;">Detalles del Pago</legend>
				<ul>
					<li>
						Fecha pago: <span style="font-weight:bold;"> ' . $_POST['fechapago'] . '</span></li>
					<li>
						Tipo de Pago : <span style="font-weight: bold;"> ' . $_POST['tipopago'] . '</span></li>
					<li>
						Banco : <span style="font-weight: bold;"> ' . $_POST['banco'] . '</span></li>
					<li>N° Operación : <span style="font-weight: bold;">' . $_POST['operacion'] . '</span></li>
					
					<li>Monto : <span style="font-weight: bold;">' . $_POST['monto'] . '</span></li>
					<li>Correo : <span style="font-weight: bold;">' . $_POST['correo'] . '</span></li>
					<li>Telefono : <span style="font-weight: bold;">' . $_POST['telefono'] . '</span></li>
					<li>Comentario : <span style="font-weight: bold;">' . $_POST['comentario'] . '</span></li>
				</ul>
			</fieldset>
		</div>
		<p>
			&nbsp;</p>
	</body>
</html>';
		$mail = new PHPMailer(  );
		$mail->IsSMTP(  );
	}

	$mail->SMTPAuth = true;
	$mensaje23 = utf8_decode( $mensaje );
	$mail->Host = $config_server['e_host'];
	$mail->Port = $config_server['e_puerto'];
	$mail->Username = $config_server['e_usuario'];
	$mail->Password = $config_server['e_pass'];
	$mail->From = $config_server['e_usuario'];
	$mail->FromName = $config_server['e_nombre'];
	$mail->AddAddress( $config_server['e_usuario'] );
	$mail->AddReplyTo( $config_server['e_usuario'] );
	$mail->WordWrap = 550;
	$mail->IsHTML( true );
	$mail->Subject = 'Confirmacion de Pago ' . $_POST['nombre'];
	$mail->MsgHTML( $mensaje23 );
	$mail->Send();
	echo '<script type="text/javascript">document.getElementById("for").style.display="none";</script>';
?>