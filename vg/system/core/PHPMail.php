<?php
namespace core;

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once __DIR__ .'/../vendor/autoload.php';

class PHPMail
{
    protected $PHPMail = false;
    protected $TypeMail = _MAIL_TYPE_;  //SMTP или mail.
    protected $smtp_port = _SMTP_PORT_; // Порт работы.
    protected $Host = _SMTP_HOST_;  //сервер для отправки почты
    protected $smtp_username = _SMTP_LOGIN_;  //Смените на адрес своего почтового ящика.
    protected $smtp_password = _SMTP_PASSWORD_;  //Измените пароль
    protected $smtp_debug = false;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
    protected $smtp_charset = 'utf-8';    //кодировка сообщений. (windows-1251 или utf-8, итд)
    protected $Debugoutput = 'html';
    protected $SMTPSecure = _SMTP_SECURE_;

    function __construct()
    {
        $this->PHPMail = $this;
    }

    public static function instance(){
        return new PHPMail;
    }

    public function setTypeMail($TypeMail='SMTP')
    {
        $this->TypeMail = $TypeMail;
    }
    public function setSmtpPort($smtp_port=25)
    {
        $this->smtp_port = $smtp_port;
    }
    public function setSmtpHost($Host = 'mail.ukraine.com.ua')
    {
        $this->Host = $Host;
    }
    public function setSmtpUserName($smtp_username = 'vladimir.gavrilov@webbrigada.ru')
    {
        $this->smtp_username = $smtp_username;
    }
    public function setSmtpPassword($smtp_password='pass')
    {
        $this->smtp_password = $smtp_password;
    }
    public function setSmtpSecure($SMTPSecure='')
    {
        $this->SMTPSecure = $SMTPSecure;
    }

    /**
     * Отправка письма с учетом настроек
     * @param $address
     * @param $title
     * @param $message
     * @param string $replyTo
     * @param array $attachment
     * @return bool
     */
    public function SendMail($address, $title, $message, $attachment = [])
    {
        $PHPMail = $this->PHPMail;
        $PHPMail->TypeMail;
        if ($PHPMail->TypeMail == 'mail') {
            return $PHPMail->sendSimpleMail($address, $title, $message, $attachment);
        } elseif ($PHPMail->TypeMail == 'SMTP') {
            return $PHPMail->sendSMTPMail($address, $title, $message, $attachment);
        }
        return false;
    }

    /**
     * Фунция отправки почтового сообщения через SMTP server
     * @param string $address
     * @param string $title
     * @param string $message
     * @param string $replyTo
     * @param array $attachment
     * @return bool
     */
    public function sendSMTPMail($address, $title, $message, $attachment = array())
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = $this->smtp_debug;
        $mail->CharSet = $this->smtp_charset;
        $mail->Debugoutput = $this->Debugoutput;
        $mail->Host = $this->Host;
        if(!empty($this->SMTPSecure)){
            $mail->SMTPSecure = $this->SMTPSecure;
        }
        $mail->Port = $this->smtp_port;
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtp_username;
        //Password to use for SMTP authentication
        $mail->Password = $this->smtp_password;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom($this->smtp_username, $this->smtp_username);
        $mail->addReplyTo($this->smtp_username, $this->smtp_username);
        if (isset($attachment) && is_array($attachment)) {
            foreach ($attachment as $file_path) {
                //$m->AddAttachment($file_path, pathinfo($file_path, PATHINFO_BASENAME));
                //$mail->addAttachment($file_path);
                if(file_exists($file_path)){
                    $mail->addStringAttachment(file_get_contents($file_path), pathinfo($file_path, PATHINFO_BASENAME));
                    unset($file_path);
                }
            }
        }
        $mail->addAddress($address);
        $mail->isHTML(true);
        //Set the subject line
        $mail->Subject = $title;
        $mail->Body = $message;

        if (!$mail->send()) {
            /*echo '<pre>';
            print_r($mail->ErrorInfo);
            echo '</pre>';*/
             echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            // echo "Message sent!";
            return true;
        }
    }

    /**
     * Отправка писем средствами функции mail()
     * @param $address
     * @param $title
     * @param $message
     * @param string $replyTo
     * @param array $attachment
     * @return bool
     */
    public function sendSimpleMail($address, $title, $message, $attachment = array())
    {
        $ttl = "=?utf-8?B?" . base64_encode($title) . "?=";
        $hdr = "Content-Type: text/html; charset=utf-8\r\n" . "From: " . $address . "\r\n" . "Date: " . date("r") . "\r\n" . "Reply-To: " . $this->smtp_username . "\r\n" . "X-Mailer: PHP/" . phpversion() . "\r\n";

        if (!empty($attachment)) {
            $boundary = md5(time());
            foreach ($attachment as $file_name => $file_path) {

                $handle = fopen($file_path, "r");
                $file_size = filesize($file_path);
                $file_type = mime_content_type($file_path);
                $content = fread($handle, $file_size);
                fclose($handle);
                $encoded_content = chunk_split(base64_encode($content));

                $hdr .= "--$boundary\r\n";
                $hdr .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
                $hdr .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
                $hdr .= "Content-Transfer-Encoding: base64\r\n";
                $hdr .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n";
                $hdr .= $encoded_content;
            }
        }
        return mail($address, $ttl, $message, $hdr);
    }
}
