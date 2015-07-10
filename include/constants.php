<?php

# Databse Infomation

// Database Server (localhost)
define("DBHOST","localhost");
// Database Username
define("DBUSER","web102-loginch"); 
// Database Password
define("DBPASS","Glasgow1");                           
// Database Name
define("DBNAME","web102-loginch");                     
// User database Table
define("DBTBLE","cw_users");   
//User Applicant Table  
define("DBAPPTBLE","applications");                      

# Location Infomation

// Path of script with trailing slashes
define("Script_Path","/logintest/");
// URL of script (no trailing slash)
define("Script_URL","http://biggreensquare.co.uk");

# System Infomation

// System Name
define("Site_Name","hrassurance.co.uk");                       
// Name on system emails
define("Email_From","Hr Assurance");                        
// Webmaster email address
define("Email_Address","barry@tech-factor.co.uk");          
// Dont reply email address
define("Non_Reply","dontreply@crispwebdesign.co.uk");              

# Session and Cookie Infomation

// Session Lifetimr in Seconds
define("Session_Lifetime", 60*60);              
// Cookie names
define("CKIEUS","USERNAME");              
define("CKIEPS","PASSWORDMD5");              

# System Settings
// Require admin approvial for new users
define("Admin_Approvial", false); // true or false

?>