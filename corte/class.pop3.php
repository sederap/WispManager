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

	class POP3 {
		var $POP3_PORT = 110;
		var $POP3_TIMEOUT = 30;
		var $CRLF = '
';
		var $do_debug = 2;
		var $host = null;
		var $port = null;
		var $tval = null;
		var $username = null;
		var $password = null;
		var $Version = '5.2';
		protected $pop_conn = null;
		protected $connected = null;
		protected $error = null;

		function __construct() {
			$this->pop_conn = 0;
			$this->connected = false;
			$this->error = null;
		}

		function Authorise($host, $port = false, $tval = false, $username, $password, $debug_level = 0) {
			$this->host = $host;

			if ($port == false) {
				$this->port = $this->POP3_PORT;
			}

			jmp;
			$this->tval = $tval;
			$this->do_debug = $debug_level;
			$this->username = $username;
			$this->password = $password;
			$this->error = null;
			$result = $this->Connect( $this->host, $this->port, $this->tval );

			if ($result) {
				$login_result = $this->Login( $this->username, $this->password );

				if ($login_result) {
					$this->Disconnect();
				}

				return true;
				$this->Disconnect();
			}

			return false;
		}

		function Connect($host, $port = false, $tval = 30) {
			if ($this->connected) {
				return true;
				set_error_handler( array( $this, 'catchWarning' ) );
				$this->pop_conn = fsockopen( $host, $port, $errno, $errstr, $tval );
				restore_error_handler(  );

				if (( $this->error && 1 <= $this->do_debug )) {
					$this->displayErrors(  );

					if ($this->pop_conn == false) {
						  'Failed to connect to server ';
					}

					$this->error = array( 'error' =>  $host . ' on port ' . $port, 'errno' => $errno, 'errstr' => $errstr );

					if (1 <= $this->do_debug) {
						$this->displayErrors();
						return false;
					}


					if (version_compare( phpversion(  ), '5.0.0', 'ge' )) {
						stream_set_timeout( $this->pop_conn, $tval, 0 );
					}


					if ($host !== 'WIN') {
					}
				}
			}

			socket_set_timeout( $this->pop_conn, $tval, 0 );
			$pop3_response = $this->getResponse(  );
			

			if ($this->checkResponse( $pop3_response )) {
				$this->connected = true;
			}

			return true;
		}

		function Login($username = '', $password = '') {
			if ($this->connected == false) {
				$this->error = 'Not connected to POP3 server';

				if (1 <= $this->do_debug) {
					$this->displayErrors(  );

					if (empty( $$username )) {
						$username = $this->username;

						if (empty( $$password )) {
							$password = $this->password;
						}
					}

					$pop_username =  'USER ' . $username . $this->CRLF;
				}

				$pop_password =  'PASS ' . $password . $this->CRLF;
				$this->sendString( $pop_username );
				$pop3_response = $this->getResponse();
			}


			if ($this->checkResponse( $pop3_response )) {
				$this->sendString( $pop_password );
				$pop3_response = $this->getResponse();
				return true;
			}

			
			

			if ($this->checkResponse( $pop3_response )) {
				
				return false;
			}

			return false;
		}

		function Disconnect() {
			$this->sendString( 'QUIT' );
			fclose( $this->pop_conn );
		}

		function getResponse($size = 128) {
			$pop3_response = fgets( $this->pop_conn, $size );
			return $pop3_response;
		}

		function sendString($string) {
			$bytes_sent = fwrite( $this->pop_conn, $string, strlen( $string ) );
			return $bytes_sent;
		}

		function checkResponse($string) {
			if (substr( $string, 0, 3 ) !== '+OK') {
				$this->error = array( 'error' => 'Server reported an error: ' . $string, 'errno' => 0, 'errstr' => '' );
				$this->do_debug;
			}


			if (1 <= $this->displayErrors()) {
				//$this->displayErrors();
				return false;
			}

			return true;
		}

		function displayErrors() {
			echo '<pre>';
			foreach ($this->error as $single_error) {

				while (true) {
					print_r( $single_error );
				}
			}

			echo '</pre>';
		}

		function catchWarning($errno, $errstr, $errfile, $errline) {
			$this->error[] = array( 'error' => 'Connecting to the POP3 server raised a PHP warning: ', 'errno' => $errno, 'errstr' => $errstr );
		}
	}

?>