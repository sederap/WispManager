<?php
include_once 'include/processes.php';
$Login_Process = new Login_Process;
$Login_Process->check_status($_SERVER['SCRIPT_NAME']);
if($_SESSION['user_level'] >= 5) {
header( 'Location: '.Script_URL.Script_Path.'admin/index.php' );}
else {header( 'Location:'.Script_URL.Script_Path.'cliente/index.php');
}
?>
