<?php
include_once '../include/admin_processes.php';
$Admin_Process = new Admin_Process;
$Admin_Process->check_status($_SERVER['SCRIPT_NAME']);
$Suspend = $Admin_Process->suspend_user($_POST, $_POST['Suspend']);
$Change = $Admin_Process->update_user($_POST, $_POST['Change']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template 
    <link href="../bootstrap/css/bootstrap.theme.css" rel="stylesheet">-->
    <!-- Bootstrap theme -->
    <link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
</head>

<body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div style="display:block; height:40px;float:left; margin-top:9px; margin-left:5px;"><a href="index.php"><img src="images/marca.png" width="140" height="30" /></a></div>
          
        </div>
        <div class="navbar-collapse collapse">
			<ul class='nav navbar-nav'>
		<li class='active'><a href='./index.php'>Inicio</a></li>
        </ul>
        <ul class='nav navbar-nav navbar-right'>
            <li class='dropdown'>
              <a href='#' class='dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-user'></span> $nombre<b class='caret'></b></a>
              <ul class='dropdown-menu'>
                <li><a href='mi-cuenta.php'><span class='glyphicon glyphicon-edit'></span> Mi Cuenta</a></li>
                <li><a href='./logout.php'><span class='glyphicon glyphicon-log-out'></span> Salir</a></li>
              </ul>
            </li>
          </ul>
		  <form class='navbar-form navbar-right' method='POST' action='resultado-busqueda.php' role='search'>
		        <div class='form-group'>
		          <input type='text' class='form-control' name='codigo' placeholder='Ingrese Cod. Producto' pattern='.{5,}' title='5 caracteres como mínimo' required>
		        </div>
		        <button type='submit' class='btn btn-default' name='buscar_producto'>Buscar</button>
		    </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	
	<!-- Wrap all page content here -->
    <div id="wrap">
		<div class="container">
			<div class="page-header">
				<h2><?php echo 'nombrePagina'; ?></h2>
			</div>
			<!--<div class="row">
				<div class="col-md-4"><div style="margin-top: 7px; margin-bottom: 5px;" id="roles"></div></div>
				<div class="col-md-5"><div id="menus"></div>
				<br>
				<input style="margin-right: 15px;" type="button" id="guardar" value="Guardar cambios" /><input id="cancelar" type="button" value="Cancelar" />
                <div style="font-size: 13px; font-family: Verdana; margin-top: 20px;" id="Events"></div>
            <div style="font-size: 13px; font-family: Verdana; margin-top: 10px;" id="CheckedItems"></div>
				</div>
				
				
				<br>
			<br>
			</div>-->
			

			
		</div> <!-- /container -->		
	</div> <!-- /wrap -->

    <!-- Bootstrap core JavaScript-->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>