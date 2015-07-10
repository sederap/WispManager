<?
error_reporting(E_ERROR | 0);
include_once 'config.php';
include_once 'mail.php';

class Client_Process {
    
    function connect_db() {
        $conn_str = mysql_connect(DBHOST, DBUSER, DBPASS);
        mysql_select_db(DBNAME, $conn_str) or die('No se pudo seleccionar Base de datos.');
    }
    
    function query($sql) {
        $this->connect_db();
        $sql = mysql_query($sql);
        $num_rows = mysql_num_rows($sql);
        $result = mysql_fetch_assoc($sql);
        return array("num_rows"=>$num_rows, "result"=>$result, "sql"=>$sql);
    }
  
function client_applicant_table() {
$username=$_SESSION['userid'];
$sql = $this->query("SELECT * FROM  applications  WHERE created_user_id = '$username'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "client_applicants_table";
$this->create_client_applicant_table($result, $num_rows, $html_id);
}


function create_client_applicant_table($result, $num_rows, $html_id) {
echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"display dataTable\" id=\"$html_id\">\n";
echo "<thead><tr class=\"table_header\">
	  		 <th align=\"center\"><strong>Id:</strong></th>
	  		 <th align=\"center\"><strong> Name:</strong></th>
	  		 <th align=\"center\"><strong> Email:</strong></th>
	  		 <th align=\"center\"><strong> Telephone:</strong></th>
	  		 <th align=\"center\"><strong> Status:</strong></th>
	  		 <th align=\"center\"><strong> Required Verification:</strong></th>
	  		 <th align=\"center\">Created</th>
			 <th align=\"center\" width=\"\">View Report</th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$applicantid=mysql_result($result,$i,"applicationsid");
$name=ucwords(substr(mysql_result($result,$i,"first_name")." ".mysql_result($result,$i,"last_name"),0,30));
$email_address=substr(mysql_result($result,$i,"email"),0,100);
$contact_no=ucwords(substr(mysql_result($result,$i,"contact_number"),0,16));
$status=ucwords(substr(mysql_result($result,$i,"status"),0,20));
$verification_required=ucwords(substr(mysql_result($result,$i,"verification_required"),0,28));
if ($status !== "Completed") { 
$downloadlink = "NA";
}
else {$downloadlink ="<a href=\"downloadreport.php?id=$applicantid\">download report</a>";}
$created=date("F j, Y", strtotime(mysql_result($result,$i,"created")));



echo "<tr>
   		  <td> $applicantid</td>
   		  <td> $name</td>
   		  <td> <a href=\"mailto:$email_address\">$email_address</a></td>
    	  <td> $contact_no</td>
    	  <td> $status</td>
    	  <td> $verification_required</td>
		  <td align=\"center\"> $created</td>
    	 <td align=\"center\">$downloadlink</td>
		  </tr>\n";     
}


echo "</table><br/>\n";

}  
    function New_Applicant($post, $process) {
		$header= Script_URL.Script_Path.'client/clientarea.php';       
        $title = $post['title'];
        $first_name = $post['first_name'];
        $initials = $post['initials'];
        $last_name = $post['last_name'];
        $dob = $post['dob'];
        $nino = $post['nino'];
        $email_address = $post['email'];
        $contact = $post['contact_number'];
        $position = $post['position'];
        $crb_checks_req = implode(", ", $post['crb_req_checks_array']);
    if (isset($_POST['crb_req'])) { $crb_req = '1';}
 	else $crb_req = '0';
 	if (isset($_POST['credit_ref_req'])) { $credit_ref_req = '1';}
 	else $credit_ref_req = '0';
 	if (isset($_POST['employment_history_req'])) { $employment_history_req = '1';}
 	else $employment_history_req = '0';
 	if (isset($_POST['academic_background_req'])) { $academic_background_req = '1';}
 	else $academic_background_req = '0';
 	if (isset($_POST['references_req"'])) { $references_req = '1';}
 	else $references_req = '0';
 	if (isset($_POST['sanctions_check_req'])) { $sanctions_check_req = '1';}
 	else $sanctions_check_req = '0'; 
		$user_id = $_SESSION['userid'];
           
       
        if ((!$first_name) || (!$last_name)) {
            return "Some Required Fields Are Missing";
        } else {
            
            $fileName = $_FILES['userfile']['name'];
            $tmpName = $_FILES['userfile']['tmp_name'];
            $fileSize = $_FILES['userfile']['size'];
            $fileType = $_FILES['userfile']['type'];
            
            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));
            $content = addslashes($content);
            fclose($fp);
            
            if (!get_magic_quotes_gpc()) {
                $fileName = addslashes($fileName);
            }
            
            $this->query("INSERT INTO `applications` (created_user_id, title, first_name, initials, last_name, dob, nino, email, contact_number, position, verification_required, cv_file_name, cv_file_type, cv_file_size, cv_file, CRB_Required, Credit_Check_Required, Academic_Verification_Required, Employment_History_Required, Reference_Check_Required, Sanctions_List_Check_Status) VALUES ('$user_id', '$title', '$first_name', '$initials', '$last_name', '$dob', '$nino', '$email_address', '$contact', '$position', '$crb_checks_req', '$fileName', '$fileType', '$fileSize', '$content', '$crb_req', '$credit_ref_req', '$academic_background_req', '$employment_history_req', '$references_req', '$sanctions_check_req' )");
            
            return header("Location: $header");
        }
    }
    
    function view_referee() {
        $sql = $this->query('SELECT * FROM `references` WHERE referenceid = '.$_GET['ref'].'');
        $result = $sql['sql'];
        $num_rows = $sql['num_rows'];
        for ($i = 0; $i < $num_rows; $i++) {
            $refid = $_GET['ref'];
            $app_firstname = mysql_result($result, $i, "first_name");
        }
    }
    
    function Submit_Reference($_POST, $submit_reference) {
        if (isset($submit_reference)) {
            $ref_company = $_POST['ref_company']; 
            $ref_position = $_POST['ref_position'];
            $ref_first_name = $_POST['ref_first_name'];
            $ref_last_name = $_POST['ref_last_name'];
            $applicants_job_title = $_POST['applicants_job_title'];
            $job_status = $_POST['job_status'];
            $job_hours = $_POST['job_hours'];
            $leaving_reason = $_POST['reasons_for_leaving'];
            $employed_from = $_POST['from_year'].'-'.$_POST['from_month'].'-'.$_POST['from_day'];
            $employed_to = $_POST['to_year'].'-'.$_POST['to_month'].'-'.$_POST['to_day'];
            $employ_again = $_POST['employ_again'];
            $employ_again_reason = $_POST['employ_again_reasons'];
            $wquality = $_POST['wquality'];
            $pknowledge = $_POST['pknowledge'];
            $initiative = $_POST['initiative'];
            $twork = $_POST['twork'];
            $tkeeping = $_POST['tkeeping'];
            $attendance = $_POST['attendence'];
            $flexibility = $_POST['flexibility'];
            $comments = $_POST['comments'];
            $id = $_POST['refid'];
			
            $this->query("UPDATE `references` SET company='$ref_company', position='$ref_position', first_name='$ref_first_name', last_name='$ref_last_name', applicant_job_title='$applicants_job_title', role_permanent='$job_status', role_full_time='$job_hours',employed_fr='$employed_from', employed_to='$employed_to', leaving_reasons='$leaving_reason', employ_again='$employ_again', employ_again_reasons='$employ_again_reason', work_quality_rating='$wquality', product_knowledge_rating='$pknowledge', initiative_rating='$initiative', team_work_rating='$twork', time_keeping_rating='$tkeeping', attendance_rating='$attendance', flexibility_rating='$flexibility', additional_info='$comments'  WHERE referenceid='".$id."'");

        
        }
    }
    
	function download_report() {
	$id=$_GET['appid'];
$sql = $this->query("SELECT * FROM `applications` WHERE idapplications = $id");
$result=$sql['sql'];
$num_rows = $sql['num_rows'];
for($i=0; $i<$num_rows; $i++){
	$size=mysql_result($result,$i,"applicant_report_file_size");
	$type=mysql_result($result,$i,"applicant_report_file_type");
	$name=mysql_result($result,$i,"applicant_report_file_name");
	$content=mysql_result($result,$i,"applicant_report");
	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$name");
	echo $content;}
}
	
    function request_reference_details($request_reference) {
        $details = $this->query('SELECT * FROM `references` WHERE referenceid = '.$_GET['refid'].'');
        return $details['result'];
		echo 'test';
    
    }
    
    function get_applicant_details($appid) {
        $applicant = $this->query("SELECT * FROM `applications` WHERE idapplications = '$appid'");
        return $applicant['result'];
    }
    
	
	


}
?>
