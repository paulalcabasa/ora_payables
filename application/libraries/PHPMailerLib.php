<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  

require_once APPPATH."/third_party/PHPMailer/class.phpmailer.php";
require_once APPPATH."/third_party/PHPMailer/class.smtp.php";

 
class PHPMailerLib extends PHPMailer {
	public function __construct() {
		parent::__construct();
		//~ $this->SMTPDebug = 3;                               // Enable verbose debug output
		$this->isSMTP();                                      // Set mailer to use SMTP
		$this->CharSet = "iso-8859-1";
		$this->Host = 'smtp.office365.com';                      // Specify main and backup SMTP servers
		$this->SMTPAuth = true;                               // Enable SMTP authentication
	//	$this->Username = 'paul-alcabasa@isuzuphil.com';                    // SMTP username
	//	$this->Password = 'alcabasa1';                           // SMTP password
		$this->Username = 'notification1@isuzuphil.com';                    // SMTP username
		$this->Password = ')OKM0okm';                           // SMTP password
		$this->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->Port = 587;                                    // TCP port to connect to
		//$this->From = 'paul-alcabasa@isuzuphil.com';
		$this->From = 'notification1@isuzuphil.com';
		$this->FromName = 'System Notification';
		$this->isHTML(true);                                  // Set 

	
	}
}
