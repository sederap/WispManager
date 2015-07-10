<?php
class formValidator{
    private $errors=array();
    public function __construct(){}
    // validate empty field
    public function validateEmpty
($field,$errorMessage,$min=4,$max=32){
        if(!isset($_POST[$field])||trim($_POST[$field])
==''||strlen($_POST[$field])<$min||strlen($_POST[$field])>$max){
            $this->errors[]=$errorMessage;
        }
    }
    // validate integer field
    public function validateInt($field,$errorMessage){
        if(!isset($_POST[$field])||!is_numeric($_POST[$field])
||intval($_POST[$field])!=$_POST[$field]){
            $this->errors[]=$errorMessage;
        }
    }
    // validate numeric field
    public function validateNumber($field,$errorMessage){
        if(!isset($_POST[$field])||!is_numeric($_POST[$field])){
            $this->errors[]=$errorMessage;
        }
    }
    // validate if field is within a range
    public function validateRange($field,$errorMessage,$min=1,$max=99){
        if(!isset($_POST[$field])||$_POST[$field]<$min||$_POST
[$field]>$max){
            $this->errors[]=$errorMessage;
        }
    }
    // validate alphabetic field
    public function validateAlphabetic($field,$errorMessage){
        if(!isset($_POST[$field])||!preg_match("/^[a-zA-Z]
+$/",$_POST[$field])){
            $this->errors[]=$errorMessage;
        }
    }
    // validate alphanumeric field
    public function validateAlphanum($field,$errorMessage){
        if(!isset($_POST[$field])||!preg_match("/^[a-zA-Z0-9]
+$/",$_POST[$field])){
            $this->errors[]=$errorMessage;
        }
    }
    // validate email
    public function validateEmail($field,$errorMessage){
        if(!isset($_POST[$field])||!preg_match
("/.+@.+\..+./",$_POST[$field])||!checkdnsrr(array_pop(explode
("@",$_POST[$field])),"MX")){
            $this->errors[]=$errorMessage;
        }
    }
    // check for errors
    public function checkErrors(){
        if(count($this->errors)>0){
            return true;
        }
        return false;
    }
    // return errors
    public function displayErrors(){
        $errorOutput='<ul>';
        foreach($this->errors as $err){
            $errorOutput.='<li>'.$err.'</li>';
        }
        $errorOutput.='</ul>';
        return $errorOutput;
    }
}
?>