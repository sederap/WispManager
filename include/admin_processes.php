<?php
error_reporting (E_ERROR | 0);
include_once 'config.php';
include_once 'mail.php';
include_once 'form_validator.php';

class Admin_Process {

function check_status($page) {

ini_set("session.gc_maxlifetime", Session_Lifetime);
session_start();

if($_SESSION['user_level'] < 4){
header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
}
}


function connect_db() {
$conn_str = mysql_connect(DBHOST, DBUSER, DBPASS);
mysql_select_db(DBNAME, $conn_str) or die ('Could not select Database.');
}

function query($sql) {

$this->connect_db();
$sql = mysql_query($sql);
$num_rows = mysql_num_rows($sql);
$result = mysql_fetch_assoc($sql);

return array("num_rows"=>$num_rows,"result"=>$result,"sql"=>$sql);
}

function traffic_lights($required, $status) {
if ($required==0){return 'na';}
else if ($status==0){return 'required';}
else if ($status==1){return 'complete';}
}


function new_user($post, $process) {

if(isset($process)) {
	
$status = "live";
$pass1			= $post['pass1'];
$pass2			= $post['pass2'];
$username		= $post['username'];
$email_address	= $post['email_address'];
$first_name		= $post['first_name'];
$last_name		= $post['last_name'];
$info			= $post['info'];
$companyid = $post['client_id'];
if ($companyid != 'new_client') {
$company 		= $post['company_name'];
$address1 		= $post['address1'];
$address2 		= $post['address2'];
$town			= $post['city'];
$postcode 		= $post['postcode'];
$telephone 		= $post['telephone'];
}

if((!$pass1) || (!$pass2) || (!$username) || (!$email_address) || (!$first_name) || (!$last_name) || (!$info)) {
return "Some Fields Are Missing";
}
if ($pass1 !== $pass2) {
return "Passwords do not match";
}
$query = $this->query("SELECT username FROM ".DBTBLE." WHERE username = '$username'");
if($query['num_rows'] > 0){
return "Username unavialable, please try a new username";
}
$query = $this->query("SELECT email_address FROM ".DBTBLE." WHERE email_address = '$email_address'");
if($query['num_rows'] > 0){
return "Emails address registered to another account.";
}


if ($clientid != 'new_client') {
$this->query("INSERT INTO company_details (company_name, address1, address2, town, postcode, telephone) VALUES ('$company', '$address1', '$address2', '$town', '$postcode', '$telephone')");
$query = "SELECT LAST_INSERT_ID() FROM company_details";
$result = mysql_query($query);
if ($result) {
$nrows = mysql_num_rows($result);
$row = mysql_fetch_row($result);
$companyid = $row[0];
}
}
$this->query("INSERT INTO ".DBTBLE." (first_name, last_name, email_address, username, password, info, status, companyid) VALUES ('$first_name', '$last_name', '$email_address', '$username', '".md5($pass1)."', '".htmlspecialchars($info)."', '$status', '$companyid')");

User_Created($username, $email_address);

if(Admin_Approvial == true) {
return 'Sign up was sucessful, your account must be reviewed by the administrator before you can login.';
} else {
return $company;
}
}

}
#company accounts table
function active_client_table() {

$sql = $this->query("SELECT * FROM `company_details` WHERE status = 'live'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "company_details_table";
$this->create_company_table($result, $num_rows, $html_id);
}

function supended_client_table() {

$sql = $this->query("SELECT * FROM `company_details` WHERE status = 'suspended'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "company_details_table";
$this->create_company_table($result, $num_rows, $html_id);
}



function create_company_table($result, $num_rows, $html_id) {
echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"display dataTable\" id=\"$html_id\">\n";
echo "<thead><tr class=\"table_header\">
	  		 <th align=\"center\"><strong> Name:</strong></th>  
	  		 <th align=\"center\"></th>  	
	  		 <th align=\"center\">Users</th>
	  		 <th align=\"center\">ADmins</th>  	  		 
	  		 <th align=\"center\">Edit</th>
			 <th align=\"center\">Password</th>
			 <th align=\"center\">Delete</th>
			 <th align=\"center\">Suspend</th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$id=mysql_result($result,$i,'companyid');
$name=mysql_result($result,$i,'company_name');
$address1=mysql_result($result,$i,'address1');
$address2=mysql_result($result,$i,'address2');
$town=mysql_result($result,$i,'town');
$postcode=mysql_result($result,$i,'postcode');
$telephone=mysql_result($result,$i,'telephone');
$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE companyid = '$id'");
$no_users=$sql['num_rows'];
$select_id = "company_$id";
echo "<tr>
   		  <td><span title=\"$address1, $address2, $town, $postcode\"> $name</span></td>
   		  <td><span class=\"telephone\" title=\"$telephone\"></span></td>
   		  <td>$no_users</td><td>";
   		  $this->list_admin_users($id);
   		 echo "</td><td align=\"center\"><a href=\"admin_edituser.php?userid=$userid&amp;user=$name\" class=\"loadexternal\" title=\"Edit User User\"><img src=\"../include/icons/edit_user.png\" alt=\"Edit Users Details\" /></a></td>
    	  <td align=\"center\"><a href=\"admin_editpass.php?userid=$userid&amp;user=$name\"class=\"loadexternal\"title=\"Change User Password\"><img src=\"../include/icons/password.png\" alt=\"Change Users Password\" /></a></td>
    	  <td align=\"center\"><a href=\"admin_deleteuser.php?id=$userid&amp;user=$name\" class=\"loadexternal\" title=\"Delete User\"><img src=\"../include/icons/remove_user.png\" alt=\"Delete User\" /></a></td>
		  <td align=\"center\"><a href=\"admin_suspenduser.php?id=$userid\">||</a></td>
		  </tr>\n";     
}
echo "</table><br/>\n";
}


#user account tables
function active_users_table() {

$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'live'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "active_users_table";
$this->create_table($result, $num_rows, $html_id);

}

function suspended_users_table() {

$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'suspended'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "suspended_users_table";
$this->create_table($result, $num_rows, $html_id);

}

function pending_users_table() {

$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'pending'");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$this->create_table($result, $num_rows);

}

function create_table($result, $num_rows, $html_id) {

echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"display dataTable\" id=\"$html_id\">\n";
echo "<thead><tr class=\"table_header\">
	  		 <th align=\"center\"><strong> Name:</strong></th>
	  		 <th align=\"center\"><strong> Email Address:</strong></th>
	  		 <th align=\"center\"><strong> Username:</strong></th>
	  		 <th align=\"center\"><strong> Info:</strong></th>
	  		 <th align=\"center\"><strong> Login:</strong></th>
	  		 <th align=\"center\"><strong> Level:</strong></th>
	  		 <th align=\"center\">Edit</th>
			 <th align=\"center\">Password</th>
			 <th align=\"center\">Delete</th>
			 <th align=\"center\">Suspend</th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$userid=mysql_result($result,$i,"userid");
$name=ucwords(substr(mysql_result($result,$i,"first_name")." ".mysql_result($result,$i,"last_name"),0,30));
$email_address=mysql_result($result,$i,"email_address");
$info=ucwords(substr(mysql_result($result,$i,"info"),0,16));
$username=ucwords(substr(mysql_result($result,$i,"username"),0,16));
$userlevel=mysql_result($result,$i,"user_level");
$last_loggedin=mysql_result($result,$i,"last_loggedin");

echo "<tr>
   		  <td> $name</td>
   		  <td> <a href=\"mailto:$email_address\">$email_address</a></td>
    	  <td> $username</td>
    	  <td> $info</td>
    	  <td align=\"center\"> $last_loggedin</td>
    	  <td align=\"center\"> $userlevel</td>
    	  <td align=\"center\"> <a href=\"admin_edituser.php?userid=$userid&amp;user=$name\" class=\"loadexternal\" title=\"Edit User User\"><img src=\"../include/icons/edit_user.png\" alt=\"Edit Users Details\" /></a></td>
    	  <td align=\"center\"> <a href=\"admin_editpass.php?userid=$userid&amp;user=$name\"class=\"loadexternal\"title=\"Change User Password\"><img src=\"../include/icons/password.png\" alt=\"Change Users Password\" /></a></td>
    	  <td align=\"center\"> <a href=\"admin_delete.php?asset=user&amp;id=$userid\" class=\"loadexternal\" title=\"Delete User\"><img src=\"../include/icons/remove_user.png\" alt=\"Delete User\" /></a></td>
		  <td align=\"center\"> <a href=\"admin_suspenduser.php?id=$userid\">||</a></td>
		  </tr>\n";     
}
echo "</table><br/>\n";
}

#new applicant table
function new_applicant_table() {
$sql = $this->query("SELECT * FROM applications left join cw_users on cw_users.userid=applications.created_user_id left join company_details on cw_users.companyid=company_details.company_id");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "new_applicants_table";
$this->create_applicant_table($result, $num_rows, $html_id);
}


function create_applicant_table($result, $num_rows, $html_id) {

echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"display dataTable\" id=\"$html_id\">\n";
echo "<thead><tr class=\"table_header\">
	  		 <th align=\"center\"><strong>Id:</strong></th>
	  		 <th align=\"center\"><strong>Created:</strong></th>
	  		 <th align=\"center\"><strong>Client:</strong></th>
	  		 <th align=\"center\"><strong> Name:</strong></th>
	  		 <th align=\"center\"><strong> Email:</strong></th>
	  		 <th align=\"center\"><strong> Tel:</strong></th>
	  		  <th align=\"center\"><strong> Status</strong></th>
	  		 <th align=\"center\"><strong> CRB</strong></th>
	  		 <th align=\"center\"><strong> CRD</strong></th>
	  		 <th align=\"center\"><strong> ACD</strong></th>
	  		 <th align=\"center\"><strong> EMP</strong></th>
	  		 <th align=\"center\"><strong> REF</strong></th>
	  		 <th align=\"center\"><strong> SAN</strong></th>
	  		 <th align=\"center\">Edit</th><th align=\"center\" width=\"\">Delete</th>
			 <th align=\"center\" width=\"\">CV</th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$applicantid=mysql_result($result,$i,"idapplications");
$name=ucwords(substr(mysql_result($result,$i,"first_name")." ".mysql_result($result,$i,"last_name"),0,30));
$email_address=ucwords(substr(mysql_result($result,$i,"email"),0,100));
$contact_no=ucwords(substr(mysql_result($result,$i,"contact_number"),0,16));
$status=ucwords(substr(mysql_result($result,$i,"status"),0,20));
$CRB_Required=mysql_result($result,$i,"CRB_Required");
$CRB_Status=mysql_result($result,$i,"CRB_Status");
$Credit_Check_Required=mysql_result($result,$i,"Credit_Check_Required");
$Credit_Check_Status=mysql_result($result,$i,"Credit_Check_Status");
$Academic_Verification_Required=mysql_result($result,$i,"Academic_Verification_Required");
$Academic_Verification_Status=mysql_result($result,$i,"Academic_Verification_Status");
$Employment_History_Required=mysql_result($result,$i,"Employment_History_Required");
$Employment_History_Status=mysql_result($result,$i,"Employment_History_Status");
$Reference_Check_Required=mysql_result($result,$i,"Reference_Check_Required");
$Reference_Check_Status=mysql_result($result,$i,"Reference_Check_Status");
$Sanctions_List_Check_Required=mysql_result($result,$i,"Sanctions_List_Check_Required");
$Sanctions_List_Check_Status=mysql_result($result,$i,"Sanctions_List_Check_Status");
$company=mysql_result($result,$i,"company_name");
if (!$company) $company="&mdash;";
if ($Reference_Check_Required != '0') { 

$sql = $this->query("SELECT * FROM `references` WHERE application_id = '$applicantid'");
$references_required=$sql['num_rows'];
$sql = $this->query("SELECT * FROM `references` WHERE application_id = '$applicantid' AND completed ='1'");
$references_complete=$sql['num_rows'];
$reference_status = $references_complete.' of '.$references_required ;
} else {$refclass=na; 
$reference_status = '';}


echo "<tr>
   		  <td> $applicantid</td>
   		  <td> $created_date</td>
   		  <td> $company</td>
   		  <td> $name</td>
   		  <td> <a href=\"mailto:$email_address\" title=\"$email_address\"><span class=\"email\"></span></a></td>
    	  <td> <a title=\"$contact_no\"><span class=\"telephone\"></a></td>
    	  <td>$status</td>
    	  <td><span class=" .$this->traffic_lights($CRB_Required,$CRB_Status). ">$CRB_Required</span> </td> 
    	  <td><span class=" .$this->traffic_lights( $Credit_Check_Required,$Credit_Check_Status). "> $Credit_Check_Required</td> 
    	  <td><span class=" .$this->traffic_lights($Academic_Verification_Required,$Academic_Verification_Status). ">$Academic_Verification_Required</span> </td> 
    	  <td><span class=" .$this->traffic_lights($Employment_History_Required,$Employment_History_Status). ">$Employment_History_Required</span> </td> 
    	  <td><span class=" .$refclass. ">$reference_status</span> </td> 
    	  <td><span class=" .$this->traffic_lights($Sanctions_List_Check_Status). ">$Sanctions_List_Check_Status </span> </td>
		  <td align=\"center\"> <a href=\"admin_manage_applicant.php?appid=$applicantid\" class=\"loadexternal\" title=\"Manage Applicant\"><span class=\"edit\"></span></a></td>
    	  <td align=\"center\"> <a href=\"admin_delete.php?id=$applicantid&amp;asset=applicant\" title=\"Delete Applicant\"><span class=\"delete\"></span></a></td>
    	  <td align=\"center\"><a href=\"downloadcv.php?id=$applicantid\"><span class=\"download\"></span></a></td>
		  </tr>\n";     
}

echo "</table><br/>\n";
}

function new_referee_table() {
$sql = $this->query('SELECT * FROM `references` WHERE application_id = '.$_GET['appid'].'');
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "referee_table";
$this->create_referee_table($result, $num_rows, $html_id);
}

function create_referee_table($result, $num_rows, $html_id) {

echo "<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"$html_id\">\n";
echo "<thead><tr class=\"table_header\">
	  		 <th align=\"center\"><strong>Id:</strong></th>
	  		 <th align=\"center\"><strong> Name</strong></th>
	  		 <th align=\"center\"><strong> Company</strong></th>
	  		 <th align=\"center\"><strong> Position</strong></th>
	  		 <th align=\"center\" class=\"icon_20\"><strong> Email</strong></th>
	  		 <th align=\"center\" class=\"icon_20\"><strong> Telephone</strong></th>
	  		 <th align=\"center\">Created</th>
			 <th align=\"center\" width=\"\">Completed</th>
			 <th class=\"icon_20\"></th>
			 <th align=\"center\" width=\"\" class=\"icon_20\"></th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$id=mysql_result($result,$i,"id");
$applicantid=mysql_result($result,$i,"application_id");
$name=ucwords(substr(mysql_result($result,$i,"first_name")." ".mysql_result($result,$i,"last_name"),0,30));
$company=ucwords(substr(mysql_result($result,$i,"company"),0,100));
$position=ucwords(substr(mysql_result($result,$i,"position"),0,100));
$email_address=mysql_result($result,$i,"email");
$telephone=ucwords(substr(mysql_result($result,$i,"telephone"),0,30));
$referee_position=ucwords(substr(mysql_result($result,$i,"position"),0,100));

$raw_date =	mysql_result($result,$i,"created");
$unix_timestamp = strtotime($raw_date);
$created_date = date('d-m-y', $unix_timestamp);
$compunf= mysql_result($result,$i,"completed");
if ($compunf == 0 ) {$completed= 'No';}
else {$completed= 'Yes';}


echo "<tr>
   		  <td> $id</td>
   		  <td> $name</td>
		  <td> $company</td>
		  <td> $position</td>
   		  <td> <a href=\"mailto:$email_address\" title=\"$email_address\" class=\"email\"></a></td>
    	  <td> <span title=\"$telephone\" class=\"telephone\"></span></td>
    	  <td> $created_date</td>
		  <td align=\"center\">$completed</td>
		  <td><a class=\"edit\"> </a></td>
		  <td><a href=\"admin_delete.php?id=$id&amp;asset=referee\" class=\"delete\"> </a></td>
		  <td><a href=\"admin_view_reference.php?refid=$id\" class=\"loadexternal\" title=\"View Reference\">view</a></td>
		  </tr>\n";     
}
echo "</table><br/>\n";
echo "<form action=\"admin_manage_applicant.php?appid=$applicantid\" enctype=\"multipart/form-data\" method=\"post\">
<table class=\"referee_table\">
<tr><td>First Name</td><td>Last Name</td><td>Company</td><td>Ref Position</td><td>Telephone</td></tr>

<tr><td><input type=\"hidden\" name=\"refid\" size=\"10\" value=\"\"/>
<input type=\"text\" name=\"reffirst_name\" size=\"10\" value=\"test\"/></td>
<td><input type=\"text\" name=\"reflast_name\" size=\"10\" value=\"\"/></td>
<td><input type=\"text\" name=\"refcompany\" size=\"10\" value=\" \"/></td>
<td><input type=\"text\" name=\"refposition\" size=\"10\" value=\" \"/></td>
<td><input type=\"text\" name=\"reftelephone\" size=\"10\" value=\"\"/></td></tr>

<tr><td>Email</td><td>Position Applicant held</td><td>from</td><td>to</td></tr>

<tr><td><input type=\"text\" name=\"refemail\" size=\"10\" value=\"\"/></td>
<td><input type=\"text\" name=\"refposheld\" size=\"10\" value=\"\"/></td>
<td><input name=\"from\" size=\"10\" value=\"\" class=\"date\"/></td>
<td><input name=\"to\" size=\"10\"  class=\"date\"/></td>
<td><input type=\"submit\" name=\"new_referee\" id=\"new_referee\" value=\"Add Referee\"/></td></tr>
</table>
	</form>";
}

function add_referee($_POST, $process) {

if(isset($process)) {
$applicationid = $_GET['appid'];
$reffirst_name = $_POST['reffirst_name'];
$reflast_name = $_POST['reflast_name'];
$refcompany = $_POST['refcompany'];
$reftelephone = $_POST['reftelephone'];
$refpos = $_POST['refposition'];
$refemail = $_POST['refemail'];
$refposheld = $_POST['refposheld'];

$this->query("INSERT INTO `references` ( first_name, last_name, application_id, position, email, telephone, applicant_job_title, company) VALUES ('$reffirst_name', '$reflast_name', '$applicationid', '$refpos', '$refemail', '$reftelephone', '$refposheld', '$refcompany')");
if (!empty($refemail)) {
$query = $this->query("SELECT LAST_INSERT_ID() FROM `references`");
$referenceid= $query['result']['LAST_INSERT_ID()'];
} 
$referee_name = $reffirst_name. " " .$reflast_name;
Mail_Reference_Request($referee_name, $referenceid,  $first_name, $last_name, $refemail, $referenceid);
}
}

function delete($asset, $id){
if ($asset = 'user'){$this->query("DELETE FROM ".DBTBLE." WHERE userid = $id");}
if ($asset = 'company'){$this->query("DELETE FROM `company_details` WHERE company_id = $id");}
$this->query("DELETE FROM `references` WHERE id = $id");
if ($asset = 'history'){$this->query("DELETE FROM `candidate_history` WHERE history_id = $id");}
if ($asset = 'applicant'){$this->query("DELETE FROM `applications` WHERE idapplications = $id");}
}

function delete_referee($refid) {
$this->query("DELETE FROM `references` WHERE id = $refid");
}

function list_users() {

$q = "SELECT * FROM ".DBTBLE."";
$result = mysql_query($q);
$num_rows = mysql_numrows($result);

echo "<select name=\"username\">";
for($i=0; $i<$num_rows; $i++){
$name=mysql_result($result,$i,"username");
echo "<option value=\"$name\">$name</option>";

}

echo "</select>";

}
function assign_account_managers($_POST){
foreach ($_POST as $key=>$value) {
if ($key != 'company_details_table_length')
$this->query("DELETE FROM `assigned_account_managers` WHERE companyid = $key");
foreach ($value as $iKey => $iValue){
$this->query("INSERT INTO `assigned_account_managers` (companyid, assigned_manager ) VALUES ('$key', '$iValue')");
print "key is $key "; print "ikey is $iKey "; print "ivalue is $iValue ";
}
}
}

function list_admin_users($id) {

$q = "SELECT * FROM ".DBTBLE." WHERE user_level >= 5";
$result = mysql_query($q);
$num_rows = mysql_numrows($result);

echo "<select name=\" {$id}[]\" id=\"$id\"class=\"example\" multiple=\"multiple\" onchange=\'this.form.submit()\'>";
for($i=0; $i<$num_rows; $i++){
$name=mysql_result($result,$i,"username");
$user_id=mysql_result($result,$i,"userid");
$sql = $this->query("SELECT * FROM `assigned_account_managers` WHERE companyid = '$id' AND assigned_manager ='$user_id'");
if ($sql['num_rows']!=0){
$selected="selected = \"selected\"";
}else{
$selected="";
}
echo "<option value=\"$user_id\" $selected>$name</option>";
}
echo  "<option value=\"12\">Test1</option>";
 "<option value=\"13\">Test2</option>";
echo "</select>";

}



function year_month_datediff($latest_date, $datetosubtract) {
$date_array = split("-",$latest_date);
$date_array2 =split("-",$datetosubtract);
$gap =  (($date_array[0]*12)+$date_array[1])-(($date_array2[0]*12)+$date_array2[1]);

return $gap;

}

function display_candidate_history($app_id) {
$sql = $this->query("SELECT * FROM  `candidate_history` WHERE applications_id = $app_id  ORDER BY from_date ASC");
$result = $sql['sql'];
$num_rows = $sql['num_rows'];
$html_id = "candidate_history_table";
$this->create_candidate_history_table($result, $num_rows, $html_id);
}

function is_candidate_history_complete($result, $num_rows){
if ($num_rows > 0){
$duration = 5;
$gap_counter = 0;
for($i=0; $i<$num_rows; $i++){
$start_date =mysql_result($result,'0',"from_date");
$to =	mysql_result($result,$i,"to_date");
$from =	mysql_result($result,$i,"from_date");
$prev_finish_date = mysql_result($result,$i-1,"to_date");
$duration_gap = $this->year_month_datediff($to,$start_date);
if ($i !==0){
$date_gap = $this->year_month_datediff($from, $prev_finish_date);}
else $date_gap = '0';
if ($date_gap > 1){
$gap_counter = $gap_counter+1;}
if (($duration_gap>$duration)AND($gap_counter==0))
return 'completed_container';
else return 'uncompleted_container';
} 
}
else return 'uncompleted_container';
}

function create_candidate_history_table($result, $num_rows, $html_id) {
$gap_counter = 0;
$class_gap = $this->is_candidate_history_complete($result, $num_rows);
echo "$class_gap<table  border=\"0\" cellspacing=\"0\" class=\"$html_id\">\n";
echo "<thead><tr>
	  		 <th><strong> From</strong></th>
	  		 <th><strong> To</strong></th>
	  		 <th><strong> Position Title</strong></th>
	  		 <th><strong> Type</strong></th>
	  		 <th class=\"icon_20\"></th>
	  		 <th class=\"icon_20\"></th>
	  		 <th class=\"icon_20\"></th>
	  		 </tr></thead>\n";   

for($i=0; $i<$num_rows; $i++){
$start_date =mysql_result($result,'0',"from_date");
$history_id=mysql_result($result,$i,"history_id");
$applicantid=mysql_result($result,$i,"applications_id");
$duration = mysql_result($result,$i,"history_length");
$from =	mysql_result($result,$i,"from_date");
$to =	mysql_result($result,$i,"to_date");
$title=mysql_result($result,$i,"title");
$type=mysql_result($result,$i,"type");
$prev_finish_date = mysql_result($result,$i-1,"to_date");
$duration_gap = $this->year_month_datediff($to,$start_date);
if ($i !==0){
$date_gap = $this->year_month_datediff($from, $prev_finish_date);}
else $date_gap = '0';
if ($date_gap > 1){
$gap_counter = $gap_counter+1;
echo"<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	  		 </table><form method=\"post\" action=\"admin_manage_applicant.php\" enctype=\"multipart/form-data\" >
<table border=\"0\" cellspacing=\"0\"  class=\"$html_id\">
<tr class=\"red\">
   		  <td> <input type=\"text\" value=\"$prev_finish_date\"  name=\"from_date\"/></td>
		  <td> <input value=\"$from\" name=\"to_date\" class=\"date\"/></td>
		  <td> <input type=\"text\" value=\" Gap\" name=\"title\"/></td>
		   <td><select name=\"type\">
  <option value=\"Employment\">Employment</option>
  <option value=\"Study\">Study</option>
  <option value=\"Other\" selected=\"selected\">Other</option>
  </select></td>
		    <td class=\"icon_20\"><input type=\"submit\" value=\"+\" name=\"new_candidate_history\" class=\" $complete_status\"/> </td>
   		 </tr></table></form><table border=\"0\" cellspacing=\"0\"  class=\"$html_id\">\n";
}



echo "<tr>
   		  <td> $from</td>
		  <td> $to</td>
		  <td> $title</td>
		   <td> $type</td>
		    <td class=\"icon_20\"><a class=\"edit\"> </a></td>
		  <td class=\"icon_20\"><a href=\"admin_delete.php?id=$history_id&amp;asset=history\" class=\"delete\"> </a></td>
		    <td> </td>
   		 </tr>\n"; 
}
echo "</table><br/>\n";
if (($duration_gap>$duration)AND($gap_counter==0)){$complete_status = 'completed_container';}
else {$complete_status ='uncompleted_container';}
}


function add_candidate_history_item($_POST, $process) {
if(isset($process)) {
$applicationid = $_GET['appid'];
$from =	$_POST['from_date'];
$to =	$_POST['to_date'];
$title=$_POST['title'];
$type =$_POST['type'];
$this->query("INSERT INTO `candidate_history` (applications_id, from_date, to_date, title, type ) VALUES ('$applicationid', '$from', '$to', '$title', '$type')");
}
}


function list_clients($show_new) {
$q = "SELECT * FROM  `company_details`" ;
$result = mysql_query($q);
$num_rows = mysql_numrows($result);

echo "<select name=\"client_id\">";
if ($show_new=1)
{
echo "<option value=\"new_client\">New Client</option>";
}
for($i=0; $i<$num_rows; $i++){
$company_id=mysql_result($result,$i,"company_id");
$company_name=mysql_result($result,$i,"company_name");
echo "<option value=\"$company_id\">$company_name</option>";
}
echo "</select>";
}

function update_user_level($POST, $change_level) {

if(isset($change_level)) {

$username = $POST['username'];
$level = $POST['level'];

$this->query("UPDATE ".DBTBLE." SET user_level = '$level' WHERE username = '$username'");

return  $username."'s User level was changed to ".$level;

}
}

function update_user($POST, $change) {

if(isset($change)) {

$username = $POST['username'];
$level = $POST['level'];

$this->query("UPDATE ".DBTBLE." SET user_level = '$level' WHERE username = '$username'");

return  $username."'s User level was changed to ".$level;

}
}

function suspend_user($id) {
if(isset($id)) {
$query = $this->query("SELECT status FROM ".DBTBLE." WHERE status='live' AND userid = '$id'");
if($query['num_rows'] > 0){
$this->query("UPDATE ".DBTBLE." SET status = 'suspended' WHERE userid = '$id'");
}
else {$this->query("UPDATE ".DBTBLE." SET status = 'live' WHERE userid = '$id'");
}
}
}

function update_applicant_verification($appid, $verification, $status) {
if ($status == '0'){
$this->query("UPDATE `applications` SET $verification = '1' WHERE idapplications = '$appid'");
}
else if ($status == '1'){
$this->query("UPDATE `applications` SET $verification = '0' WHERE idapplications = '$appid'");
}
}

function delete_user($_POST, $delete) {

if(isset($delete)) {

$check = $_POST['check'];
$id = $_POST['id'];

if ($check == "yes") {

$this->query("DELETE FROM ".DBTBLE." WHERE userid = $id");

return  "User was deleted.<br /><a href=\"admin_center.php\">Admin Center</a>";

} else if ($check == "no") {

return  "User was not deleted.<br /><a href=\"admin_center.php\">Admin Center</a>";

}

} else {
return "Are you sure you want to delete the user?";
}
}


function edit_user($_POST, $edit) {

if(isset($edit)) {

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$info = $_POST['info'];
$email_address = $_POST['email_address'];
$username = $_POST['username'];
$userid = $_POST['userid'];
$level = $_POST['level'];

$this->query("UPDATE ".DBTBLE." SET first_name='$first_name', last_name='$last_name', email_address='$email_address', info='$info', username='$username', user_level = '$level' WHERE userid='".$userid."'");

return "User Details Updated.<br /><a href=\"admin_center.php\">Admin Center</a>";

}
}

function edit_request($edit) {

if(isset($edit)) {
$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['userid'].'');
return $details['result'];
} else {
$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['userid'].'');
return $details['result'];
}

}

function request_referee($referee) {

if(isset($referee)) {
$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['refid'].'');
return $details['result'];
} else {
$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['refid'].'');
return $details['result'];
}

}

function edit_application_request($edit) {
if(isset($edit)) {
$details = $this->query('SELECT * FROM `applications` WHERE idapplications = '.$_GET['appid'].'');
return $details['result'];
} else {
$details = $this->query('SELECT * FROM `applications` WHERE idapplications = '.$_GET['appid'].'');
return $details['result'];
}
}

function edit_application($_POST, $process) {
        if (isset($process))
        {
                
            $fileName = $_FILES['userfile']['name'];
            $tmpName = $_FILES['userfile']['tmp_name'];
            $fileSize = $_FILES['userfile']['size'];
            $fileType = $_FILES['userfile']['type'];
            
            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));
            $content = addslashes($content);
            fclose($fp);

                $status=$_POST["status"];

                $hmm=$this->query("update applications set applicant_report=\"$content\",applicant_report_file_type=\"$fileType\",applicant_report_file_size=\"$fileSize\",applicant_report_file_name=\"$fileName\", status=\"$status\" where  idapplications = " . $_GET['appid']);

        }
}

function edit_pass($_POST, $edit) {

if(isset($edit)) {

$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];
$userid = $_POST['userid'];

if ($pass1 !== $pass2) {
return "Passwords do not match.";
}

$this->query("UPDATE ".DBTBLE." SET password = '".md5($pass1)."' WHERE userid = '$userid'");

return "User password was updated.<br /><a href=\"admin_center.php\">Admin Center</a>";

}
}

function delete_applicant($_POST, $delete) {

if(isset($delete)) {

$check = $_POST['check'];
$id = $_POST['id'];

if ($check == "yes") {

$this->query("DELETE FROM ".applications." WHERE idapplications = $id");
} else if ($check == "no") {

return  "Applicant was not deleted.<br /><a href=\"applicant_admin.php.php\">Admin Center</a>";

}

} else {
return "Are you sure you want to delete the user?";
}
}

function download_cv() {
$id=$_GET['id'];
$sql = $this->query("SELECT * FROM `applications` WHERE idapplications = $id");
$result=$sql['sql'];
$num_rows = $sql['num_rows'];
for($i=0; $i<$num_rows; $i++){
$size=mysql_result($result,$i,"cv_file_size");
$type=mysql_result($result,$i,"cv_file_type");
$name=mysql_result($result,$i,"cv_file_name");
$content=mysql_result($result,$i,"cv_file");
header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
echo $content;}
}

function download_applicant_report() {
$id=$_GET['id'];
$sql = $this->query("SELECT * FROM `applications` WHERE idapplications = 106");
$result=$sql['sql'];
$num_rows = $sql['num_rows'];
for($i=0; $i<$num_rows; $i++){
$size=mysql_result($result,$i,"applicant_report_file_size");
$type=mysql_result($result,$i,"applicant_report_file_type");
$name=mysql_result($result,$i,"applicant_reportfile_name");
$content=mysql_result($result,$i,"applicant_reportfile");
header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
echo $content;}
}
}



?>