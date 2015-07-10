<?php 
function menu($id){
	
	$id_usuario = datosUsuario($id)->id_usuario;
	
	$db = DataBase::conectar();
	$db->setQuery("select us.rol, rm.id_menu, um.menu, um.submenu, um.url, um.orden, us.nombre_usuario 
		from usuarios us 
		inner join roles_menu rm on us.rol = rm.id_rol 
		inner join menus um on um.id_menu = rm.id_menu where um.estado = 1 and us.id_usuario = $id order by orden");
	$menus = $db->loadObjectList();

	$salida_menu = "<ul class='nav navbar-nav'>";
		//<li class='active'><a href='./index.php'>Inicio</a></li>";

	$menuActual = '';
	$usoSubmenu = 0;
	
	foreach($menus as $m){
		$id_menu = "menu".$m->id_menu;
		$submenu = $m->submenu;
		$menu = $m->menu;
		$url = $m->url;
		$nombre = ucfirst($m->nombre_usuario);

		if ($submenu == '-'){
			if ($usoSubmenu > 0){
				$salida_menu .= "</ul></li>";
			}
			$salida_menu .= "<li><a href='$url'>$menu</a></li>";
			$nombre_menu = $_SESSION['sfpy_usuario'];
		}else{
			if ($menu != $menuActual){
				if ($usoSubmenu > 0){
					$salida_menu .= "</ul></li>";
				}
				$salida_menu .= "<li class='dropdown'>
				  <a href='#' class='dropdown-toggle' data-toggle='dropdown'>$menu<b class='caret'></b></a>
				  <ul class='dropdown-menu'>";
				
					$salida_menu .= "<li><a href='$url'>$submenu</a></li>";
				 
				  $menuActual = $menu;
			}else{
				$usoSubmenu++;
					$salida_menu .= "<li><a href='$url'>$submenu</a></li>";

			}
		}
	}

	##Nuevo menu de usuario
	$salida_menu .= "</ul></ul>
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
          ";
	echo $salida_menu;
}

?>