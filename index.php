<?php
/**
*
* @ WispManager v1.0
* @ PHP 5.3
* @ Author     : Sederap
* @ Website    : http://TecnetPeru.net
*
**/

include_once 'include/processes.php';
$Login_Process = new Login_Process;
$Login_Process->check_login($_GET['page']);
$Login = $Login_Process->log_in($_POST['Username'], $_POST['Password'], $_POST['remember'], $_POST['page'], $_POST['Submit']); 
?>
<!doctype html>
<html lang="es" class="login_page">
<head>
<meta charset="utf-8">
<title><?=Site_Name ?></title>
<!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<!-- Stylesheets -->
    <link rel="stylesheet" href="css/login.css" />
	<!--<link rel="stylesheet" href="admin/css/base.css">
	<link rel="stylesheet" href="admin/css/skeleton.css">
	<link rel="stylesheet" href="admin/css/layout.css">-->
</head>

<body>
	<div class="notice" style="display:none" id="notice">
   <a href="#" class="close" onclick="$('#notice').hide();">close</a><p class="warn">Ingrese usuario y Contraseña!!</p>
	</div>
    
    <div class="notice" style="display:none" id="notice2">
   <a href="#" class="close" onclick="$(\'#notice2\').hide();">close</a><p class="warn">usuario o Clave incorrectos!!</p>
	</div>

    <div class="notice" style="display:none" id="notice3">
   <a href="#" class="close" onclick="$(\'#notice3\').hide();">close</a><p class="warn">No se ha ingresado Ninguna Licencia!!</p>
	</div>
    
    <div class="notice" style="display:none" id="notice4">
   <a href="#" class="close" onclick="$(\'#notice4\').hide();">close</a><p class="warn">Error de Licencia,Verifique su Conexión a Inernet!!</p>
	</div>   
    
        <div class="notice" style="display:none" id="notice5">
   <a href="#" class="close" onclick="$(\'#notice5\').hide();">close</a><p class="warn">Licencia Vencida!!</p>
	</div>   
    
        <div class="notice" style="display:none" id="notice6">
   <a href="#" class="close" onclick="$(\'#notice6\').hide();">close</a><p class="warn">Licencia Bloqueada!!</p>
	</div>   
   
    <div class="notice" style="display:none" id="notice7">
   <a href="#" class="close" onclick="$(\'#notice7\').hide();">close</a><p class="warn">Sistema Restaurado!!</p>
	</div>   
<?php      

				if ($_GET['msg'] == '1') {
					echo '<script>$(\'#notice\').show();</script>';
				}
			if ($_GET['msg'] == '2') {
				echo '<script>$(\'#notice2\').show();</script>';
			}
			if ($_GET['msg'] == '3') {
				echo '<script>$(\'#notice3\').show();</script>';
			}
			if ($_GET['msg'] == '4') {
				echo '<script>$(\'#notice4\').show();</script>';
			}
	if ($_GET['msg'] == '5') {
		echo '<script>$(\'#notice5\').show();</script>';
		}
		if ($_GET['msg'] == '6') {
			echo '<script>$(\'#notice6\').show();</script>';
		}
	if ($_GET['msg'] == 'restaurado') {
		echo '<script>$(\'#notice7\').show();</script>';
		}

?>	
<style type="text/css">
.classname {
	-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
	-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
	box-shadow:inset 0px 1px 0px 0px #97c4fe;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0));
	background:-moz-linear-gradient(center top, #3d94f6 5%, #1e62d0 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3d94f6', endColorstr='#1e62d0');
	background-color:#3d94f6;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #337fed;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:12px;
	font-weight:bold;
	font-style:normal;
	height:30px;
	line-height:30px;
	width:100px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #1570cd;
}
.classname:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #1e62d0), color-stop(1, #3d94f6));
	background:-moz-linear-gradient(center top, #1e62d0 5%, #3d94f6 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e62d0', endColorstr='#3d94f6');
	background-color:#1e62d0;
}
.classname:active {
	position:relative;
	top:1px;
}
.checkbox {
        margin-bottom: 10px;
      }


</style>	
<!--<div class="container">-->
		
<div class="form-bg">
        
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <img src="admin/images/logo_login.png" alt="" width="180" height="37" style="margin-top:5px; margin-left:80px"/>
            <h2></h2>
			  <div class="seccion"><input name="Username" type="text" autofocus id="Username" placeholder="Usuario" ></div>
			  <div class="seccion"><input name="Password" type="password" id="Password" placeholder="Contraseña"></div>
<input name="page" type="hidden" value="<?php echo $_GET['page']; ?>" />
<!--<br/>
<label class="checkbox">Recuérdame
<input name="remember" type="checkbox" value="true" />
</label>-->

				<button type="submit" class="classname" name="Submit">Ingresar</button>
			</form>
		</div>
<!--       </div>-->
       
</body>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="admin/js/jquery.min.js"></script>
</html>