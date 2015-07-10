<?php
error_reporting (E_ERROR | all);
include 'config.php';
include 'mail.php';
if(isset($_GET['log_out'])) {
	$Login_Process = new Login_Process;
	$Login_Process->log_out($_SESSION['username'], $_SESSION['password']); 
	}

class Login_Process {

	var $cookie_user = CKIEUS;
	var $cookie_pass = CKIEPS;

	function connect_db() {

		$conn_str = mysql_connect(DBHOST, DBUSER, DBPASS);
		mysql_select_db(DBNAME, $conn_str) or die ('Could not select Database.');

	}

	function querydb($sql) {

		$this->connect_db();
		$sql = mysql_query($sql);
		$num_rows = mysql_num_rows($sql);
		$result = mysql_fetch_assoc($sql);
			
	return array("num_rows"=>$num_rows,"result"=>$result,"sql"=>$sql);
	
	}
	function welcome_note() {
			
		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();
			
		if(isset($_COOKIE[$this->cookie_user]) && isset($_COOKIE[$this->cookie_pass])) {		
			$this->log_in($_COOKIE[$this->cookie_user], $_COOKIE[$this->cookie_pass], 'true', 'false', 'cookie'); 
		}
		if(isset($_SESSION['username'])) { 
			return "<a href=\"".Script_URL.Script_Path."main.php\">Welcome ".$_SESSION['first_name']."</a>";
		} else {
			return "<a href=\"".Script_URL.Script_Path."index.php\">Welcome Guest, Please Login</a>";
		}	
	}
	
	function check_login($page) {

		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();

		if(isset($_COOKIE[$this->cookie_user]) && isset($_COOKIE[$this->cookie_pass])){
			$this->log_in($_COOKIE[$this->cookie_user], $_COOKIE[$this->cookie_pass], 'true', $page, 'cookie'); 
		} else if(isset($_SESSION['username'])) { 	
			if(!$page) { $page = Script_Path."main.php"; }
					//header("Location: http://".$_SERVER['HTTP_HOST'].$page); 
		} else {
		    return true;
		}
	}

	function check_status($page) {

		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();

		if(!isset($_SESSION['username'])){
			header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page); 
		}
	}

	function log_in($username, $password, $remember, $page, $submit) {
		
		if(isset($submit)) {

		if($submit !== "cookie") {
			$password = md5($password);
		}

		$querydb = $this->querydb("SELECT * FROM ".DBTBLE." WHERE username='$username' AND password='$password'");

		if($querydb['num_rows'] == 1) {

			if ($querydb['result']['status'] == "suspended") {
				return "Account Suspended, <br /> Contact System Administrator.";
			}
			if ($querydb['result']['status'] == "pending") {
				return "Account Pending, <br /> Administrator has not yet approved your account.";
			}
				$this->set_session($username, $password);	
				if(isset($remember)) 
				{ $this->set_cookie($username, $password, '+');	}
							
		} else {
				return "Username or Password not reconised.";
		}			
			$this->querydb("UPDATE ".DBTBLE." SET last_loggedin = '".date ("d/m/y G:i:s")."' WHERE username = '$username'");
		
		if(!$page) { $page = Script_Path."main.php"; }
			
		if ($page == 'false') {
				return true;
		} else {
				header("Location: http://".$_SERVER['HTTP_HOST'].$page); 
		}
		
		}
	}
	
	function set_session($username, $password) {
	
			$querydb = $this->querydb("SELECT * FROM ".DBTBLE." WHERE username='$username' AND password='$password'");
	
			ini_set("session.gc_maxlifetime", Session_Lifetime); 
			session_start();

			$_SESSION['userid']        = $querydb['result']['userid'];
			$_SESSION['first_name']    = $querydb['result']['first_name'];
			$_SESSION['last_name']     = $querydb['result']['last_name'];
			$_SESSION['email_address'] = $querydb['result']['email_address'];
			$_SESSION['username']      = $querydb['result']['username'];
			$_SESSION['info']          = $querydb['result']['info'];
			$_SESSION['user_level']    = $querydb['result']['user_level'];
			$_SESSION['password']      = $querydb['result']['password'];
			

	}	
	
	function set_cookie($username, $password, $set) {

			if($set == "+")
				{ $cookie_expire = time()+60*60*24*30; }
			else 
				{ $cookie_expire = time()-60*60*24*30; }		
	
			setcookie($this->cookie_user, $username, $cookie_expire, '/');
			setcookie($this->cookie_pass, $password, $cookie_expire, '/');
	
	} 

	function log_out($username, $password, $header) {

	session_start();
	session_unset();
	session_destroy();
    	$this->set_cookie($username, $password, '-');

		if(!isset($header)) {
			header('Location: ../index.php');
		} else {
			return true;
		}
	
	}

	function edit_details($post, $process) {

		if(isset($process)) {
			
		$first_name		= $post['first_name'];
		$last_name		= $post['last_name'];
		$email_address	= $post['email_address'];
		$info			= $post['info'];
		$username		= $post['username'];
		$password		= $_SESSION['password'];
		
		if((!$first_name) || (!$last_name) || (!$email_address) || (!$info)) {
			return "Por favor ingrese todos los detalles.";
		}

		$this->querydb("UPDATE ".DBTBLE." SET first_name = '$first_name', last_name = '$last_name', 
		email_address = '$email_address', info = '$info' WHERE username = '$username'");		

				$this->set_session($username, $password);		
				if(isset($_COOKIE[$this->cookie_pass])) 
				{ $this->set_cookie($username, $pass, '+'); }

				return "Detalles cambiaron con éxito.";
		}
	}

	function edit_password($post, $process) {

		if(isset($process)) {

		$pass1		= $post['pass1'];
		$pass2		= $post['pass2'];
		$password	= $post['pass'];
		$username	= $post['username'];
		
		if ((!$password) || (!$pass1) || (!$pass2)) {
			return "Faltan datos requeridos.";
		} 
		if (md5($password) !== $_SESSION['password']) {
			return "Contraseña actual es incorrecta.";
		}
		if ($pass1 !== $pass2) {
			return "Las nuevas contraseñas no coinciden.";
		}

		$new = md5($pass1);
		$this->querydb("UPDATE ".DBTBLE." SET password = '$new' WHERE username = '$username'");

				$this->set_session($username, $new);		
				if(isset($_COOKIE[$this->cookie_pass])) 
				{ $this->set_cookie($username, $pass, '+'); }

			return "Actualización Contraseña éxito.";
		}
	}

	function Register($post, $process) {

		if(isset($process)) {

		$pass1			= $post['pass1'];
		$pass2			= $post['pass2'];
		$username		= $post['username'];
		$email_address	= $post['email_address'];
		$first_name		= $post['first_name'];
		$last_name		= $post['last_name'];
		$info			= $post['info'];
		$company 		= $post['company_name'];
		$address1 		= $post['address1'];
		$address2 		= $post['address2'];
		$town			= $post['city'];
		$postcode 		= $post['postcode'];
		$telephone 		= $post['telephone'];
		
		if((!$pass1) || (!$pass2) || (!$username) || (!$email_address) || (!$first_name) || (!$last_name) || (!$info)) {
		return "Some Fields Are Missing";
		}
		if ($pass1 !== $pass2) {
		return "Passwords do not match";
		}
		$querydb = $this->querydb("SELECT username FROM ".DBTBLE." WHERE username = '$username'");
		if($querydb['num_rows'] > 0){
		return "Username unavialable, please try a new username";
		}
		$querydb = $this->querydb("SELECT email_address FROM ".DBTBLE." WHERE email_address = '$email_address'");
		if($querydb['num_rows'] > 0){
		return "Emails address registered to another account.";
		}
		
		if(Admin_Approvial == true) {
		$status = "pending";
		} else {
		$status = "live";
		}
		
		$this->querydb("INSERT INTO company_details (company_name, address1, address2, town, postcode, telephone) VALUES ('$company', '$address1', '$address2', '$town', '$postcode', '$telephone')");
		$querydb = "SELECT LAST_INSERT_ID() FROM company_details";
		$result = mysql_query($querydb);
		if ($result) {
    	$nrows = mysql_num_rows($result);
    	$row = mysql_fetch_row($result);
    	$companyid = $row[0];
    	}
				
		$this->querydb("INSERT INTO ".DBTBLE." (first_name, last_name, email_address, username, password, info, status, companyid) VALUES ('$first_name', '$last_name', '$email_address', '$username', '".md5($pass1)."', '".htmlspecialchars($info)."', '$status', '$companyid')");
		
		User_Created($username, $email_address);
		
		if(Admin_Approvial == true) {
		return 'Regístrate fue exitoso, su cuenta debe ser revisada por el administrador antes de poder iniciar sesión.';
		} else {
		return $company;
		} 	
	}
	
	} 
	
	function Forgot_Password($get, $post) {
	
	$username = $post['username'];
	if(!$username) { 
	$username = $get['username']; } 
	
	$code = $post['code'];
	if(!$code) { 
	$code = $get['code']; } 

		if (isset($code)) {
			$querydb = $this->querydb("SELECT * FROM ".DBTBLE." WHERE username='$username' AND forgot='$code'");
		
		if($querydb['num_rows'] == 1) {		
				return "<!-- !-->";
		} else {
		if(isset($code) && isset($username)) {
				return "Link Invalid, Please Request a new link.";
		} else {
				return false;
		}
	}
	}
}

	function Request_Password($post, $process) {
		
		$username = $post['username'];
		$email = $post['email'];
			
		if(isset($process)) {

		$querydb = $this->querydb("SELECT * FROM ".DBTBLE." WHERE username='$username' AND email_address = '$email'");

			if((!$username) || (!$email)) {
				return "Please enter all details.";
			}

			if($querydb['num_rows'] == 0){
				return "Matching details were not found.";
			}

		    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
		    srand((double)microtime()*1000000);
		    $i = 0;
		    $pass = '' ;

   			while ($i <= 7) {
   			    $num = rand() % 33;
        		$tmp = substr($chars, $num, 1);
        		$pass = $pass . $tmp;
        		$i++;
    		}
			$code = md5($pass);
			$this->querydb("UPDATE ".DBTBLE." SET forgot = '$code' WHERE username='$username' AND email_address='$email'");

			Mail_Reset_Password($username, $code, $email);
				return "We have sent an email to your address, this will allow you to reset your password.";
			
		}
	}

	function Reset_Password($post, $process) {

		if(isset($process)) {
		
		$pass1 = $post['pass1'];
		$pass2 = $post['pass2'];
		$username = $post['username'];
		
		$querydb = $this->querydb("SELECT * FROM ".DBTBLE." WHERE username='$username'");
		$email = $querydb['result']['email_address'];
				
		if ($pass1 !== $pass2) {
			return "New passwords do not match";
		}
		
			$password = md5($pass1);

		$querydb = $this->querydb("UPDATE ".DBTBLE." SET password = '$password', forgot = 'NULL' WHERE username = '$username'");
		
		Mail_Reset_Password_Confirmation($username, $email);
		return "Password Reset, You may now login. '.$email.'" ;

		}
	} 
}

?>