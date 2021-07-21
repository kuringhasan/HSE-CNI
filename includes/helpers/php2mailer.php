<?php
/**
* @package DCISTEM - Core
* @subpackage Helper - PHP Mailer
*
* @author Eka Rafi Dimasyono <rafee12@yahoo.com>
*/

defined("PANDORA") OR die("No direct access allowed.");

class php2mailer {

	private $to         = array();
	private $cc         = array();
	private $bcc        = array();
	private $subject    = "";
	private $header     = array();
	private $html       = false;
	private $template   = "";
	private $message    = "";
	private $attachment = array();
	private $mail       = null;

	public function __construct() {
   //
//    }
//    public function build() {
		global $dcistem;
		$path  = str_replace("\\", "/", dirname(__FILE__));
		$path .= (substr($path, -1) <> "/" ? "/" : "")."php_mailer/class.phpmailer.php";
		if(!file_exists($path)) {
			trigger_error("File 'helpers/php_mailer/class.phpmailer.php' Not Found 123!", E_USER_ERROR);
		}
        
		include_once $path;
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$dcistem->loadConfig("php_mailer", array("stop_if_error" => true));
		$config           = $dcistem->getOption("php_mailer");
		$mail->Host       = $config["hostname"];
		$mail->Port       = $config["port"];
		$mail->Username   = $config["username"];
		$mail->Password   = $config["password"];
		$mail->SMTPAuth   = $config["auth"];
		$mail->SMTPSecure = $config["secure"];
		$this->mail = $mail;
	}

	public function from($email, $name = "") {
		$this->mail->SetFrom($email, $name);
	}

	public function to($email, $name = "") {
		$this->mail->AddAddress($email, $name);
	}

	public function reply_to($email, $name = "") {
		$this->mail->AddReplyTo($email, $name);
	}

	public function cc($email, $name = "") {
		$this->mail->AddCC($email, $name);
	}

	public function bcc($email, $name = "") {
		$this->mail->AddBCC($email, $name);
	}

	public function subject($subject) {
		$this->mail->Subject = $subject;
	}

	public function message($message, $template = "") {
		if(!empty($template) && is_array($message)) {
			reset($message);
			while($each = each($message)) {
			$template = str_replace("{".$each["key"]."}", $each["value"], $template);
			}
			$message = $template;
		}
		$this->mail->Body = $message;
	}

	public function attachment($filepath) {
		$this->mail->AddAttachment($filepath, basename($filepath), "base64", Mime::get($filepath));
	}

	public function send($html = false) {
		$this->mail->IsHTML($html);
		return $this->mail->Send();
	}

}
?>