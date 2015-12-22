<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
  
class My_PHPMailer {  
    public function My_PHPMailer() {  
        require_once('PHPMailer/PHPMailerAutoload.php');  
		
    }  

    public function new_mail(){
    	$mail             = new PHPMailer();
		$mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = "fmt01.web.com.ph";      // setting GMail as our SMTP server
        $mail->Port       = 465;                   // SMTP port to connect to GMail
        $mail->Username   = "resumebox@upcapes.org";  // user email address
        $mail->Password   = "capes2015";   
		return $mail;
    }
}  