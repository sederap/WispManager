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

	class SMTP {
		var $SMTP_PORT = 25;
		var $CRLF = '
';
		var $do_debug = null;
		var $do_verp = false;
		var $Version = '5.2';
		protected $smtp_conn = null;
		protected $error = null;
		protected $helo_rply = null;

		function __construct() {
			$this->smtp_conn = 0;
			$this->error = null;
			$this->helo_rply = null;
			$this->do_debug = 0;
		}

		function Connect($host, $port = 0, $tval = 30) {
			$this->error = null;

			if ($this->connected(  )) {
				$this->error = array( 'error' => 'Already connected to a server' );
				return false;

				if (empty( $$port )) {
					$this->SMTP_PORT;
					$port = ;
					$this->smtp_conn = @fsockopen( $host, $port, $errno, $errstr, $tval );

					if (empty( $this->smtp_conn )) {
						array( 'error' => 'Failed to connect to server', 'errno' => $errno, 'errstr' => $errstr );
					}
				}
			}

			$this->error = ;

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $this->error['error'] . (  . ': ' . $errstr . ' (' . $errno . ')' ) . $this->CRLF . '<br />';
				return false;

				if (substr( PHP_OS, 0, 3 ) != 'WIN') {
					socket_set_timeout( $this->smtp_conn, $tval, 0 );
					$this->get_lines(  );
					$announce = ;
					$this->do_debug;
				}
			}


			if (2 <= ) {
				'SMTP -> FROM SERVER:' . $announce . $this->CRLF . '<br />';
			}

			echo ;
			return true;
		}

		function StartTLS() {
			$this->error = null;

			if (!$this->connected(  )) {
				$this->error = array( 'error' => 'Called StartTLS() without being connected' );
				return false;
				fputs( $this->smtp_conn, 'STARTTLS' . $this->CRLF );
				$this->get_lines(  );
				$rply = ;
				substr;
			}

			( $rply, 0, 3 );
			$code = ;

			if (2 <= $this->do_debug) {
				echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';

				if ($code != 220) {
					$this->error = array( 'error' => 'STARTTLS not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

					if (1 <= $this->do_debug) {
						'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply;
					}
				}
			}

			echo  . $this->CRLF . '<br />';
			return false;
		}

		function Authenticate($username, $password) {
			fputs( $this->smtp_conn, 'AUTH LOGIN' . $this->CRLF );
			$this->get_lines(  );
			$rply = ;
			substr( $rply, 0, 3 );
			$code = ;

			if ($code != 334) {
				$this->error = array( 'error' => 'AUTH not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

				if (1 <= $this->do_debug) {
					echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF . '<br />';
					return false;
					fputs;
				}
			}

			( $this->smtp_conn, base64_encode( $username ) . $this->CRLF );
			$this->get_lines(  );
			$rply = ;
			substr( $rply, 0, 3 );
			$code = ;

			if ($code != 334) {
				$this->error = array( 'error' => 'Username not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

				if (1 <= $this->do_debug) {
					$this->error;
				}
			}

			echo 'SMTP -> ERROR: ' . ['error'] . ': ' . $rply . $this->CRLF . '<br />';
			return false;
		}

		function Connected() {
			if (!empty( $this->smtp_conn )) {
				socket_get_status;
				$this->smtp_conn;
			}

			(  );
			$sock_status = ;

			if ($sock_status['eof']) {
				if (1 <= $this->do_debug) {
					echo 'SMTP -> NOTICE:' . $this->CRLF . 'EOF caught while checking if connected';
					$this->Close(  );
					return false;
					return true;
				}
			}

			return false;
		}

		function Close() {
			$this->error = null;
			$this->helo_rply = null;

			if (!empty( $this->smtp_conn )) {
				fclose;
				$this->smtp_conn;
			}

			(  );
			$this->smtp_conn = 0;
		}

		function Data($msg_data) {
			$this->error = null;

			if (!$this->connected(  )) {
				$this->error = array( 'error' => 'Called Data() without being connected' );
				return false;
				fputs( $this->smtp_conn, 'DATA' . $this->CRLF );
				$this->get_lines(  );
				$rply = ;
				substr( $rply, 0, 3 );
				$code = ;

				if (2 <= $this->do_debug) {
				}
			}


			while (true) {
				echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';

				if ($code != 354) {
					$this->error = array( 'error' => 'DATA command not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

					if (1 <= $this->do_debug) {
						echo 'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF . '<br />';
						return false;
					}

					str_replace( '
', '
', $msg_data );
					$msg_data = ;
					str_replace( '', '
', $msg_data );
					$msg_data = ;
					explode( '
', $msg_data );
					$lines = ;
					substr( $lines[0], 0, strpos( $lines[0], ':' ) );
					$field = ;
					$in_headers = false;

					if (( !empty( $$field ) && !strstr( $field, ' ' ) )) {
						$in_headers = true;
						$max_line_length = 1672;
						@each( $lines )[1];
						$line = ;

						if () {
							$lines_out = null;

							if (( $line == '' && $in_headers )) {
								$in_headers = false;

								if ($max_line_length < strlen( $line )) {
									strrpos( substr( $line, 0, $max_line_length ), ' ' );
									$pos = ;

									if (!$pos) {
										$pos = $max_line_length - 1;
										substr;
										$line;
										0;
										$pos;
									}
								}
							}
						}
					}
				}


				while (true) {
					$lines_out[] = (  );
					substr( $line, $pos );
					$line = ;
					break 2;
				}

				$lines_out[] = $line;
				@each( $lines_out )[1];
				$line_out = ;

				if () {
					if (0 < strlen( $line_out )) {
						if (substr( $line_out, 0, 1 ) == '.') {
							$line_out = '.' . $line_out;
							fputs;
							$this->smtp_conn;
						}
					}

					( $line_out . $this->CRLF );
					break;
				}
			}

			fputs( $this->smtp_conn, $this->CRLF . '.' . $this->CRLF );
			$this->get_lines(  );
			$rply = ;
			substr( $rply, 0, 3 );
			$code = ;

			if (2 <= $this->do_debug) {
				echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';

				if ($code != 250) {
					$this->error = array( 'error' => 'DATA not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

					if (1 <= $this->do_debug) {
						'SMTP -> ERROR: ' . $this->error['error'] . ': ';
					}
				}

				 . $rply;
			}

			echo  . $this->CRLF . '<br />';
			return false;
		}

		function Hello($host = '') {
			$this->error = null;

			if (!$this->connected(  )) {
				$this->error = array( 'error' => 'Called Hello() without being connected' );
				return false;

				if (empty( $$host )) {
					$host = 'localhost';
					$this->SendHello;
				}
			}


			if (!( 'EHLO', $host )) {
			}


			if (!$this->SendHello( 'HELO', $host )) {
			}

			return false;
		}

		function SendHello($hello, $host) {
			fputs( $this->smtp_conn, $hello . ' ' . $host . $this->CRLF );
			$this->get_lines(  );
			$rply = ;
			substr( $rply, 0, 3 );
			$code = ;

			if (2 <= $this->do_debug) {
				'SMTP -> FROM SERVER: ' . $rply;
			}

			echo  . $this->CRLF . '<br />';

			if ($code != 250) {
				$this->error = array( 'error' => $hello . ' not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

				if (1 <= $this->do_debug) {
					'SMTP -> ERROR: ' . $this->error['error'];
				}

				 . ': ';
			}

			echo  . $rply . $this->CRLF . '<br />';
			return false;
		}

		function Mail($from) {
			$this->error = null;

			if (!$this->connected(  )) {
				$this->error = array( 'error' => 'Called Mail() without being connected' );
				return false;
				$this->do_verp;
			}


			if () {
				$useVerp = (true ? 'XVERP' : '');
				fputs;
				$this->smtp_conn;
				'MAIL FROM:<' . $from . '>' . $useVerp;
			}

			(  . $this->CRLF );
			$this->get_lines(  );
			$rply = ;
			substr( $rply, 0, 3 );
			$code = ;

			if (2 <= $this->do_debug) {
				echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';

				if ($code != 250) {
					$this->error = array( 'error' => 'MAIL not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );
				}
			}


			if (1 <= $this->do_debug) {
				'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply;
				$this->CRLF;
			}

			echo  .  . '<br />';
			return false;
		}

		function Quit($close_on_error = true) {
			$this->error = null;

			if (!$this->connected(  )) {
				$this->error = array( 'error' => 'Called Quit() without being connected' );
				return false;
				fputs( $this->smtp_conn, 'quit' . $this->CRLF );
				$this->get_lines(  );
				$byemsg = ;

				if (2 <= $this->do_debug) {
					echo 'SMTP -> FROM SERVER:' . $byemsg . $this->CRLF . '<br />';
					$rval = true;
					$e = null;
					substr( $byemsg, 0, 3 );
					$code = ;

					if ($code != 221) {
						array( 'error' => 'SMTP server rejected quit command', 'smtp_code' => $code );
						substr( $byemsg, 4 );
					}

					$e = array( 'smtp_rply' =>  );
				}
			}

			$rval = false;

			if (1 <= $this->do_debug) {
				echo 'SMTP -> ERROR: ' . $e['error'] . ': ' . $byemsg . $this->CRLF . '<br />';
			}

			function Recipient($to) {
				(bool)$to;
				$this->error = null;

				if (!$this->connected(  )) {
					$this->error = array( 'error' => 'Called Recipient() without being connected' );
					return false;
					fputs( $this->smtp_conn, 'RCPT TO:<' . $to . '>' . $this->CRLF );
					$this->get_lines(  );
					$rply = ;
					substr( $rply, 0, 3 );
					$code = ;

					if (2 <= $this->do_debug) {
						'SMTP -> FROM SERVER:' . $rply;
						$this->CRLF;
					}

					echo  .  . '<br />';
				}


				if (( $code != 250 && $code != 251 )) {
					$this->error = array( 'error' => 'RCPT not accepted from server', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );
					$this->do_debug;
				}


				if (1 <= ) {
					'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply;
					$this->CRLF;
				}

				echo  .  . '<br />';
				return false;
			}

			function Reset() {
				$this->error = null;

				if (!$this->connected(  )) {
					$this->error = array( 'error' => 'Called Reset() without being connected' );
					return false;
					fputs( $this->smtp_conn, 'RSET' . $this->CRLF );
					$this->get_lines(  );
					$rply = ;
					substr( $rply, 0, 3 );
					$code = ;

					if (2 <= $this->do_debug) {
						echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';
					}
				}


				if ($code != 250) {
				}

				$this->error = array( 'error' => 'RSET failed', 'smtp_code' => $code, 'smtp_msg' => substr( $rply, 4 ) );

				if (1 <= $this->do_debug) {
					'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF;
				}

				echo  . '<br />';
				return false;
			}

			function SendAndMail($from) {
				$this->error = null;

				if (!$this->connected(  )) {
					$this->error = array( 'error' => 'Called SendAndMail() without being connected' );
					return false;
					fputs( $this->smtp_conn, 'SAML FROM:' . $from . $this->CRLF );
					$this->get_lines(  );
					$rply = ;
					substr( $rply, 0, 3 );
					$code = ;

					if (2 <= $this->do_debug) {
						echo 'SMTP -> FROM SERVER:' . $rply . $this->CRLF . '<br />';
					}
				}


				if ($code != 250) {
					array( 'error' => 'SAML not accepted from server', 'smtp_code' => $code );
				}

				$this->error = array( 'smtp_msg' => substr( $rply, 4 ) );

				if (1 <= $this->do_debug) {
					'SMTP -> ERROR: ' . $this->error['error'] . ': ' . $rply . $this->CRLF . '<br />';
				}

				echo ;
				return false;
			}

			function Turn() {
				$this->error = array( 'error' => 'This method, TURN, of the SMTP ' . 'is not implemented' );

				if (1 <= $this->do_debug) {
					'SMTP -> NOTICE: ' . $this->error['error'] . $this->CRLF;
				}

				echo  . '<br />';
				return false;
			}

			function getError() {
				return $this->error;
			}

			function get_lines() {
				$data = '';
				@fgets( $this->smtp_conn, 515 );

				if ($str = ) {
					if (4 <= $this->do_debug) {
						(  . 'SMTP -> get_lines(): $data was "' . $data . '"' );
						$this->CRLF;
					}
				}

				echo  .  . '<br />';
				echo (  . 'SMTP -> get_lines(): $str is "' . $str . '"' ) . $this->CRLF . '<br />';
				$data .= $data;

				if (4 <= $this->do_debug) {
					 . 'SMTP -> get_lines(): $data is "' . $data;
				}

				echo (  . '"' ) . $this->CRLF . '<br />';

				if (substr( $str, 3, 1 ) == ' ') {
					break;
					return $data;
				}
			}
		}

		return 1;
	}
?>