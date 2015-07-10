<?php
error_reporting (E_ERROR | 0);
include 'config.php';


$headers =  'From: '.Email_Address.'' . "\r\n" .
		    'Reply-To: '.Email_Address.'' . "\r\n";

// Email Sent when a user requests to reset their password
function Mail_Reset_Password($username, $code, $email) {

	$subject = 'Password Request';
	
	$message = '
	Dear '.$username.',
	
	Your password for '.Site_Name.' has been requested.
	To reset your password please follow the link below.
	'.Script_URL.Script_Path.'forgotpassword.php?username='.$username.'&code='.$code.'
	If you didnt request your password please delete this email.
	
	Thanks 
		'.Email_From.'';

		mail($email, $subject, $message, $headers, '-f'.Email_Address);

}

function Mail_Reference_Request($referee, $id, $app_first_name, $app_last_name,  $email, $referenceid) {

	$subject = 'Reference Request for '.$app_first_name.' '.$app_last_name.'';
	
	$message = '
	Dear '.$referee.',
	
	You have been named as a referee for '.$app_first_name.' '.$app_last_name.'
	Please Complete the reference form at the link below
	'.Script_URL.Script_Path.'client/reference.php?refid='.$referenceid.'

	
	Thanks 
		'.Email_From.'';

		mail($email, $subject, $message, $headers, '-f'.Email_Address);

}

// Email sent if the user resets the password with the password recovery tool

function Mail_Reset_Password_Confirmation($username, $email) {

	$subject = 'Password Reset';
	
	$message = '
	Dear '.$username.',
	
	Your password for '.Site_Name.' has been reset.
	If you did not request that your password was reset please contact the administrator.
	
	Thanks 
		'.Email_From.'';
	
		mail($email, $subject, $message, $headers);

}

// Email sent when a user signs up to the system
function User_Created($username, $email) {

	$subject = 'Welcome to '.Site_Name;
	
	$message = '
	Dear '.$username.',

	Thanks for signing up to the '.Site_Name.'.';
	
	// if admin approvial is true.
	if(Admin_Approvial == true) {
		$message .= 'You will recieve an email from the administrator once your account had been approved, 
					 You can then login with your username and password at '.Script_URL.Script_Path.'.';
	
	// If admin approvial is false
	} else {
		$message .= 'You can login with your username and password at '.Script_URL.Script_Path.'. 
		
		';
		
	}
	$message .= '
	
	Kind Regards
	'.Email_From.'';
		mail($email, $subject, $message, $headers, '-f'.Email_Address);

}

// Email sent to client if the admin changes their status
function Status_Changed($username, $email, $status) {

	$subject = 'Welcome to '.Site_Name;
	
	$message = '
	Dear '.$username.',
	
	The administrator has changed your account status to '.$status.',
	Should you have any enquiries please contact the administrator.
		
	Thanks 
		'.Email_From.'';

		mail($email, $subject, $message, $headers, '-f'.Email_Address);

}
function Reference_Created($email, $referencee) {

}
?>